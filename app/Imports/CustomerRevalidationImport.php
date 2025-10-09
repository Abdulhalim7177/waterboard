<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Lga;
use App\Models\Ward;
use App\Models\Area;
use App\Models\Category;
use App\Models\Tariff;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerRevalidationImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Map common possible column names for customer data
            $email = $row['email'] ?? $row['customer_email'] ?? $row['email_address'] ?? null;
            $phone = $row['phone'] ?? $row['phone_number'] ?? $row['mobile'] ?? $row['customer_phone'] ?? null;
            $firstName = $row['first_name'] ?? $row['fname'] ?? $row['first_name_'] ?? $row['customer_first_name'] ?? 'Unknown';
            $surname = $row['surname'] ?? $row['last_name'] ?? $row['lname'] ?? $row['sname'] ?? $row['customer_surname'] ?? 'Unknown';
            $streetName = $row['street'] ?? $row['street_name'] ?? $row['address'] ?? $row['location'] ?? '';
            $houseNumber = $row['house_number'] ?? $row['house_no'] ?? $row['hse_no'] ?? '';
            $landmark = $row['landmark'] ?? $row['land_mark'] ?? '';
            
            // Location mapping
            $lgaName = $row['lga'] ?? $row['lga_name'] ?? $row['lga_nm'] ?? '';
            $wardName = $row['ward'] ?? $row['ward_name'] ?? $row['ward_nm'] ?? '';
            $areaName = $row['area'] ?? $row['area_name'] ?? $row['area_nm'] ?? '';
            
            // Service details
            $categoryName = $row['category'] ?? $row['customer_category'] ?? $row['category_name'] ?? '';
            $tariffName = $row['tariff'] ?? $row['tariff_name'] ?? $row['tariff_type'] ?? '';
            
            // Map status values to allowed database values ('pending', 'approved', 'rejected')
            $rawStatus = $row['status'] ?? $row['customer_status'] ?? $row['approval_status'] ?? 'pending';
            $status = $this->mapStatusValue($rawStatus);
            
            $waterSupplyStatus = $row['water_supply_status'] ?? $row['supply_status'] ?? $row['water_status'] ?? 'Functional';
            
            // Location coordinates
            $latitude = $row['latitude'] ?? $row['lat'] ?? $row['customer_latitude'] ?? null;
            $longitude = $row['longitude'] ?? $row['lng'] ?? $row['long'] ?? $row['customer_longitude'] ?? null;
            
            // Find or create LGA
            $lga = null;
            if (!empty($lgaName)) {
                $lga = Lga::where('name', $lgaName)->first();
                if (!$lga) {
                    // If LGA doesn't exist, we might need to handle this differently
                    // For now, we'll skip if essential location data is missing
                    continue;
                }
            }
            
            // Find or create Ward
            $ward = null;
            if ($lga && !empty($wardName)) {
                $ward = Ward::where('lga_id', $lga->id)
                           ->where('name', $wardName)
                           ->first();
            }
            
            // Find Area (no new areas created - only existing ones)
            $area = null;
            if ($ward && !empty($areaName)) {
                $area = Area::where('ward_id', $ward->id)
                           ->where('name', $areaName)
                           ->first();
            }
            
            // Find or create Category
            $category = null;
            if (!empty($categoryName)) {
                $category = Category::where('name', $categoryName)->first();
            }
            
            // Find or create Tariff
            $tariff = null;
            if ($category && !empty($tariffName)) {
                $tariff = Tariff::where('category_id', $category->id)
                               ->where('name', $tariffName)
                               ->first();
            }
            
            // Only process if we have essential identifying information
            if (empty($email) && empty($phone)) {
                continue; // Skip rows without email or phone
            }
            
            // Update or create the customer
            $customerQuery = Customer::whereNotNull('id'); // start with a base query
            
            if (!empty($email)) {
                $customerQuery = $customerQuery->where('email', $email);
            }
            
            if (!empty($phone)) {
                $customerQuery = $customerQuery->orWhere('phone_number', $phone);
            }
            
            $customer = $customerQuery->first();
            
            if ($customer) {
                // Update existing customer with revalidation data
                $updateData = [
                    'first_name' => $firstName,
                    'surname' => $surname,
                    'street_name' => $streetName,
                    'house_number' => $houseNumber,
                    'landmark' => $landmark,
                    'status' => $status,
                    'water_supply_status' => $waterSupplyStatus
                ];
                
                if ($lga) $updateData['lga_id'] = $lga->id;
                if ($ward) $updateData['ward_id'] = $ward->id;
                if ($area) $updateData['area_id'] = $area->id;
                if ($category) $updateData['category_id'] = $category->id;
                if ($tariff) $updateData['tariff_id'] = $tariff->id;
                if ($latitude) $updateData['latitude'] = $latitude;
                if ($longitude) $updateData['longitude'] = $longitude;
                
                $customer->update($updateData);
            } else {
                // Generate unique default values if email or phone are empty
                $generatedEmail = $email;
                if (empty($generatedEmail)) {
                    $generatedEmail = 'temp_' . time() . '_' . rand(1000, 9999) . '@example.com';
                }
                
                $generatedPhone = $phone;
                if (empty($generatedPhone)) {
                    $generatedPhone = '0' . rand(700000000, 999999999); // Generate a realistic phone number
                }
                
                // Create new customer if doesn't exist (for new customers in revalidation)
                $customerData = [
                    'first_name' => $firstName,
                    'surname' => $surname,
                    'email' => $generatedEmail,
                    'phone_number' => $generatedPhone,
                    'street_name' => $streetName,
                    'house_number' => $houseNumber,
                    'landmark' => $landmark,
                    'status' => $status,
                    'water_supply_status' => $waterSupplyStatus,
                    'password' => bcrypt('password') // Default password for revalidation
                ];
                
                if ($lga) $customerData['lga_id'] = $lga->id;
                if ($ward) $customerData['ward_id'] = $ward->id;
                if ($area) $customerData['area_id'] = $area->id;
                if ($category) $customerData['category_id'] = $category->id;
                if ($tariff) $customerData['tariff_id'] = $tariff->id;
                if ($latitude) $customerData['latitude'] = $latitude;
                if ($longitude) $customerData['longitude'] = $longitude;
                
                Customer::create($customerData);
            }
        }
    }
    
    private function mapStatusValue($rawStatus)
    {
        $rawStatus = strtolower(trim($rawStatus));
        
        // Map various possible status values to the allowed database values
        if (in_array($rawStatus, ['approved', 'active', 'activated', 'verified', 'completed', 'active'])) {
            return 'approved';
        } elseif (in_array($rawStatus, ['rejected', 'denied', 'cancelled', 'inactive', 'deactivated'])) {
            return 'rejected';
        } else {
            // Default to 'pending' for values like 'pending', 'submitted', 'submitted_via_web', etc.
            // or if the value is unrecognized
            return 'pending';
        }
    }
}