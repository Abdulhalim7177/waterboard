<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Models\Category;
use App\Models\Tariff;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;


class CustomerImport implements ToCollection, WithHeadingRow, WithValidation, WithChunkReading
{
    protected $errors = [];

    public function prepareForValidation($row, $index)
    {
        return [
            ...$row,
            'phone_number' => isset($row['phone_number']) ? (string) $row['phone_number'] : null,
            'alternate_phone_number' => isset($row['alternate_phone_number']) ? (string) $row['alternate_phone_number'] : null,
            'house_number' => isset($row['house_number']) ? (string) $row['house_number'] : null,
        ];
    }


    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                $email = $row['email'] ?? null;
                $phone = $row['phone_number'] ?? null;

                if (empty($email) && empty($phone)) {
                    $this->errors[] = "Row " . ($index + 2) . ": Skipping row without email or phone number.";
                    continue;
                }

                $customerQuery = Customer::query();
                if (!empty($email)) {
                    $customerQuery->orWhere('email', $email);
                }
                if (!empty($phone)) {
                    $customerQuery->orWhere('phone_number', $phone);
                }
                $customer = $customerQuery->first();

                $lga = Lga::firstOrCreate(['name' => $row['lga']], ['status' => 'approved']);
                $ward = $row['ward'] && $lga ? Ward::firstOrCreate(['name' => $row['ward'], 'lga_id' => $lga->id], ['status' => 'approved']) : null;
                $area = $row['area'] && $ward ? Area::firstOrCreate(['name' => $row['area'], 'ward_id' => $ward->id], ['status' => 'approved']) : null;
                $category = Category::firstOrCreate(['name' => $row['category']], ['status' => 'approved']);
                $tariff = $row['tariff'] && $category ? Tariff::firstOrCreate(['name' => $row['tariff'], 'category_id' => $category->id], ['price' => 0, 'billing_type' => 'flat', 'status' => 'approved']) : null;

                if (!$lga) {
                    $this->errors[] = "Row " . ($index + 2) . ": Invalid LGA: {$row['lga']}";
                    continue;
                }
                if (!$ward) {
                    $this->errors[] = "Row " . ($index + 2) . ": Invalid Ward: {$row['ward']} for LGA {$row['lga']}";
                    continue;
                }
                if (!$area) {
                    $this->errors[] = "Row " . ($index + 2) . ": Invalid Area: {$row['area']} for Ward {$row['ward']}";
                    continue;
                }
                if (!$category) {
                    $this->errors[] = "Row " . ($index + 2) . ": Invalid Category: {$row['category']}";
                    continue;
                }
                if (!$tariff) {
                    $this->errors[] = "Row " . ($index + 2) . ": Invalid Tariff: {$row['tariff']} for Category {$row['category']}";
                    continue;
                }
                
                $billing_condition = 'Non-Metered';
                if ($tariff) {
                    if (strtolower($tariff->billing_type) !== 'flat') {
                        $billing_condition = 'Metered';
                    }
                }

                $data = [
                    'first_name' => $row['first_name'],
                    'surname' => $row['surname'],
                    'middle_name' => $row['middle_name'] ?? null,
                    'street_name' => $row['street_name'] ?? 'N/A', // Default to 'N/A' if missing
                    'house_number' => $row['house_number'] ?? 'N/A', // Default to 'N/A' if missing
                    'landmark' => $row['landmark'] ?? null,
                    'lga_id' => $lga->id,
                    'ward_id' => $ward->id,
                    'area_id' => $area->id,
                    'category_id' => $category->id,
                    'tariff_id' => $tariff->id,
                    'delivery_code' => $row['delivery_code'] ?? null,
                    'billing_condition' => $billing_condition,
                    'water_supply_status' => $this->mapWaterSupplyStatusValue($row['water_supply_status'] ?? 'Functional'),
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                    'altitude' => $row['altitude'] ?? null,
                    'pipe_path' => $this->parsePolygonCoordinates($row['pipe_path'] ?? null),
                    'polygon_coordinates' => $this->parsePolygonCoordinates($row['polygon_coordinates'] ?? null),
                    'status' => 'pending',
                    'account_balance' => $row['account_balance'] ?? 0,
                ];

                if ($customer) {
                    // Update existing customer
                    $customer->update($data);
                    Log::info('Customer updated', ['customer_id' => $customer->id]);
                } else {
                    // Create new customer
                    $existingCustomer = Customer::where('email', $email)->orWhere('phone_number', $phone)->first();
                    if ($existingCustomer) {
                        $this->errors[] = "Row " . ($index + 2) . ": A customer with this email or phone number already exists.";
                        continue;
                    }

                    $data['email'] = $email;
                    $data['phone_number'] = $phone;
                    $data['password'] = Hash::make($row['password'] ?? 'default123');
                    $data['created_by'] = Auth::guard('staff')->id();
                    $data['created_at'] = $row['created_at'] ? Carbon::parse($row['created_at']) : now();
                    
                    Customer::create($data);
                    Log::info('Customer created', ['email' => $email]);
                }
            } catch (\Exception $e) {
                $this->errors[] = "Row " . ($index + 2) . ": Error processing row: {$e->getMessage()}";
                Log::error('Customer import failed for a row', ['error' => $e->getMessage()]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|min:10|regex:/^[0-9]+$/',
            'alternate_phone_number' => 'nullable|string|min:10|regex:/^[0-9]+$/',
            'street_name' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'lga' => 'required|string',
            'ward' => 'required|string',
            'area' => 'required|string',
            'category' => 'required|string',
            'tariff' => 'required|string',
            'delivery_code' => 'nullable|string|max:255',
            'billing_condition' => 'required|in:Metered,Non-Metered',
            'water_supply_status' => 'required|in:Functional,Non-Functional',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'altitude' => 'nullable|numeric',
            'pipe_path' => 'nullable|string',
            'polygon_coordinates' => 'nullable|string',
            'password' => 'nullable|string|min:8',
            'account_balance' => 'nullable|numeric|min:0',
            'created_at' => 'nullable|date',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'first_name.required' => 'First Name is required.',
            'surname.required' => 'Surname is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'phone_number.required' => 'Phone Number is required.',
            'phone_number.regex' => 'Phone Number must contain only digits.',
            'phone_number.unique' => 'The phone number has already been taken.',
            'alternate_phone_number.regex' => 'Alternate Phone Number must contain only digits.',
            'alternate_phone_number.unique' => 'The alternate phone number has already been taken.',
            'lga.required' => 'LGA is required.',
            'ward.required' => 'Ward is required.',
            'area.required' => 'Area is required.',
            'category.required' => 'Category is required.',
            'tariff.required' => 'Tariff is required.',
            'billing_condition.required' => 'Billing Condition is required.',
            'billing_condition.in' => 'Billing Condition must be Metered or Non-Metered.',
            'water_supply_status.required' => 'Water Supply Status is required.',
            'water_supply_status.in' => 'Water Supply Status must be Functional or Non-Functional.',
            'latitude.required' => 'Latitude is required.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.required' => 'Longitude is required.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
            'altitude.numeric' => 'Altitude must be a number.',
            'account_balance.numeric' => 'Account Balance must be a number.',
            'account_balance.min' => 'Account Balance cannot be negative.',
            'created_at.date' => 'Created At must be a valid date.',
            'polygon_coordinates.string' => 'Polygon Coordinates must be a valid string (JSON or semicolon-separated lat,lng pairs).',
        ];
    }

    public function chunkSize(): int
    {
        return 500; // Process 500 rows at a time
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function parsePolygonCoordinates($input)
    {
        if (empty($input)) {
            return null;
        }

        // Try JSON decoding first
        $coords = json_decode($input, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($coords)) {
            if (empty($coords) || collect($coords)->every(fn($point) => is_array($point) && count($point) >= 2 && is_numeric($point[0]) && is_numeric($point[1]))) {
                return array_map(fn($point) => [(float) $point[0], (float) $point[1]], $coords);
            }
        }

        // Handle semicolon-separated format (e.g., "12.941289 7.599873 0 0;12.941075 7.599733 0 0")
        $points = array_filter(explode(';', trim($input, ';')));
        $coords = [];
        foreach ($points as $point) {
            $values = array_map('trim', array_filter(explode(' ', trim($point))));
            if (count($values) >= 2 && is_numeric($values[0]) && is_numeric($values[1])) {
                $coords[] = [(float) $values[0], (float) $values[1]]; // Only take lat,lng, ignore extra values
            } else {
                return null;
            }
        }
        return !empty($coords) ? $coords : null;
    }


    private function mapWaterSupplyStatusValue($rawStatus)
    {
        $rawStatus = strtolower(trim($rawStatus));

        if (in_array($rawStatus, ['non-functional', 'not functional', 'broken', 'disconnected', 'inactive'])) {
            return 'Non-Functional';
        } else {
            return 'Functional';
        }
    }
}
