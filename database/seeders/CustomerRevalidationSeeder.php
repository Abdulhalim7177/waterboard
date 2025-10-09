<?php

namespace Database\Seeders;

use App\Imports\CustomerRevalidationImport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class CustomerRevalidationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $possiblePaths = [
            database_path('Katsina_Water_Board_Customer_Revalidation_Form_-_all_versions_-_labels_-_2025-05-27-15-53-08.xlsx'),
            base_path('docs/Katsina_Water_Board_Customer_Revalidation_Form_-_all_versions_-_labels_-_2025-05-27-15-53-08.xlsx'),
            base_path('Katsina_Water_Board_Customer_Revalidation_Form_-_all_versions_-_labels_-_2025-05-27-15-53-08.xlsx')
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (File::exists($path)) {
                $filePath = $path;
                break;
            }
        }

        if (!$filePath) {
            $this->command->error("Customer revalidation Excel file not found in any expected location!");
            $this->command->info("Looked for file in:");
            $this->command->info("- " . database_path('Katsina_Water_Board_Customer_Revalidation_Form_-_all_versions_-_labels_-_2025-05-27-15-53-08.xlsx'));
            $this->command->info("- " . base_path('docs/Katsina_Water_Board_Customer_Revalidation_Form_-_all_versions_-_labels_-_2025-05-27-15-53-08.xlsx'));
            $this->command->info("- " . base_path('Katsina_Water_Board_Customer_Revalidation_Form_-_all_versions_-_labels_-_2025-05-27-15-53-08.xlsx'));
            return;
        }

        try {
            // Import the customer revalidation data
            Excel::import(new CustomerRevalidationImport, $filePath);

            $this->command->info('Customer revalidation data imported successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error importing customer revalidation data: ' . $e->getMessage());
            throw $e; // Re-throw to see the full error
        }
    }
}