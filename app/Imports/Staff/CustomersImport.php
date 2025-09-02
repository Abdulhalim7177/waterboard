<?php

namespace App\Imports\Staff;

use App\Models\Customer;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Models\Category;
use App\Models\Tariff;
use App\Models\PendingCustomerUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithPreparingForValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
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

    public function model(array $row)
    {
        try {
            // Map row headers to database fields
            $lga = Lga::where('name', $row['lga'])->where('status', 'approved')->first();
            $ward = $row['ward'] && $lga ? Ward::where('name', $row['ward'])->where('lga_id', $lga->id)->where('status', 'approved')->first() : null;
            $area = $row['area'] && $ward ? Area::where('name', $row['area'])->where('ward_id', $ward->id)->where('status', 'approved')->first() : null;
            $category = Category::where('name', $row['category'])->where('status', 'approved')->first();
            $tariff = $row['tariff'] && $category ? Tariff::where('name', $row['tariff'])->where('category_id', $category->id)->where('status', 'approved')->first() : null;

            // Validate relationships
            if (!$lga) {
                $this->errors[] = "Invalid LGA: {$row['lga']} for row with email {$row['email']}";
                return null;
            }
            if (!$ward) {
                $this->errors[] = "Invalid Ward: {$row['ward']} for LGA {$row['lga']} in row with email {$row['email']}";
                return null;
            }
            if (!$area) {
                $this->errors[] = "Invalid Area: {$row['area']} for Ward {$row['ward']} in row with email {$row['email']}";
                return null;
            }
            if (!$category) {
                $this->errors[] = "Invalid Category: {$row['category']} for row with email {$row['email']}";
                return null;
            }
            if (!$tariff) {
                $this->errors[] = "Invalid Tariff: {$row['tariff']} for Category {$row['category']} in row with email {$row['email']}";
                return null;
            }

            // Validate and parse polygon_coordinates if provided
            if (!empty($row['polygon_coordinates'])) {
                $coords = $this->parsePolygonCoordinates($row['polygon_coordinates']);
                if (!$coords) {
                    $this->errors[] = "Invalid polygon coordinates format in row with email {$row['email']}. Must be valid JSON or semicolon-separated lat,lng pairs (e.g., '12.941289 7.599873;12.941075 7.599733').";
                    return null;
                }
                $row['polygon_coordinates'] = json_encode($coords);
            }

            // Prepare customer data
            $customerData = [
                'first_name' => $row['first_name'],
                'surname' => $row['surname'],
                'middle_name' => $row['middle_name'] ?? null,
                'email' => $row['email'],
                'phone_number' => $row['phone_number'],
                'alternate_phone_number' => $row['alternate_phone_number'] ?? null,
                'street_name' => $row['street_name'],
                'house_number' => $row['house_number'],
                'landmark' => $row['landmark'] ?? null,
                'lga_id' => $lga->id,
                'ward_id' => $ward->id,
                'area_id' => $area->id,
                'category_id' => $category->id,
                'tariff_id' => $tariff->id,
                'delivery_code' => $row['delivery_code'] ?? null,
                'billing_condition' => $row['billing_condition'],
                'water_supply_status' => $row['water_supply_status'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'altitude' => $row['altitude'] ?? null,
                'pipe_path' => $row['pipe_path'] ?? null,
                'polygon_coordinates' => $row['polygon_coordinates'] ?? null,
                'password' => Hash::make($row['password'] ?? 'default123'),
                'status' => 'pending',
                'account_balance' => $row['account_balance'] ?? 0,
                'created_at' => $row['created_at'] ? Carbon::parse($row['created_at']) : now(),
                'created_by' => Auth::guard('staff')->id(),
            ];

            // Use transaction to ensure data integrity
            return DB::transaction(function () use ($customerData) {
                // Create customer
                $customer = Customer::create($customerData);

                // Log pending updates for approval
                $fields = [
                    'first_name', 'surname', 'middle_name', 'email', 'phone_number', 'alternate_phone_number',
                    'street_name', 'house_number', 'landmark', 'lga_id', 'ward_id', 'area_id',
                    'category_id', 'tariff_id', 'delivery_code', 'billing_condition', 'water_supply_status',
                    'latitude', 'longitude', 'altitude', 'pipe_path', 'polygon_coordinates', 'account_balance'
                ];

                $pendingUpdates = [];
                foreach ($fields as $field) {
                    $value = $customer->$field;
                    if (!is_null($value)) {
                        $pendingUpdates[] = [
                            'customer_id' => $customer->id,
                            'field' => $field,
                            'old_value' => null,
                            'new_value' => is_array($value) ? json_encode($value) : $value,
                            'updated_by' => Auth::guard('staff')->id(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
                if (!empty($pendingUpdates)) {
                    PendingCustomerUpdate::insert($pendingUpdates);
                }

                Log::info('Customer imported and pending approval', ['customer_id' => $customer->id, 'email' => $customerData['email']]);
                return $customer;
            });
        } catch (\Exception $e) {
            $this->errors[] = "Error processing row with email {$row['email']}: {$e->getMessage()}";
            Log::error('Customer import failed for row', ['email' => $row['email'], 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'phone_number' => 'required|string|min:10|regex:/^[0-9]+$/|unique:customers,phone_number',
            'alternate_phone_number' => 'nullable|string|min:10|regex:/^[0-9]+$/|unique:customers,alternate_phone_number',
            'street_name' => 'required|string|max:255',
            'house_number' => 'required|string|max:255',
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
            'pipe_path' => 'nullable|string|max:255',
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
            'email.unique' => 'Email already exists in the database.',
            'phone_number.required' => 'Phone Number is required.',
            'phone_number.regex' => 'Phone Number must contain only digits.',
            'phone_number.unique' => 'Phone Number already exists in the database.',
            'alternate_phone_number.regex' => 'Alternate Phone Number must contain only digits.',
            'alternate_phone_number.unique' => 'Alternate Phone Number already exists in the database.',
            'street_name.required' => 'Street Name is required.',
            'house_number.required' => 'House Number is required.',
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
}