<?php

namespace Database\Seeders;

use App\Models\Lga;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LgaWardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Disable foreign key constraints temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Clear existing LGA and Ward data (in the right order to respect foreign key constraints)
            DB::table('wards')->delete();
            DB::table('lgas')->delete();
            
            // Re-enable foreign key constraints
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Hardcoded Katsina State LGA data
            $lgas = [
                ['code' => '30917C', 'name' => 'Bakori', 'status' => 1],
                ['code' => '48330C', 'name' => 'Batagarawa', 'status' => 1],
                ['code' => '30918C', 'name' => 'Batsari', 'status' => 1],
                ['code' => '48331C', 'name' => 'Baure', 'status' => 1],
                ['code' => '30919C', 'name' => 'Bindawa', 'status' => 1],
                ['code' => '30920C', 'name' => 'Charanchi', 'status' => 1],
                ['code' => '30921C', 'name' => 'Dandume', 'status' => 1],
                ['code' => '30922C', 'name' => 'Danja', 'status' => 1],
                ['code' => '48332C', 'name' => 'Danmusa', 'status' => 1],
                ['code' => '30923C', 'name' => 'Daura', 'status' => 1],
                ['code' => '30924C', 'name' => 'Dutsi', 'status' => 1],
                ['code' => '30925C', 'name' => 'Dutsin Ma', 'status' => 1],
                ['code' => '30926C', 'name' => 'Faskari', 'status' => 1],
                ['code' => '30927C', 'name' => 'Funtua', 'status' => 1],
                ['code' => '30928C', 'name' => 'Ingawa', 'status' => 1],
                ['code' => '30929C', 'name' => 'Jibia', 'status' => 1],
                ['code' => '30930C', 'name' => 'Kafur', 'status' => 1],
                ['code' => '30931C', 'name' => 'Kaita', 'status' => 1],
                ['code' => '30932C', 'name' => 'Kankara', 'status' => 1],
                ['code' => '30933C', 'name' => 'Kankia', 'status' => 1],
                ['code' => '30934C', 'name' => 'Katsina', 'status' => 1],
                ['code' => '30935C', 'name' => 'Kurfi', 'status' => 1],
                ['code' => '30936C', 'name' => 'Kusada', 'status' => 1],
                ['code' => '30937C', 'name' => 'Mai Adua', 'status' => 1],
                ['code' => '30938C', 'name' => 'Malumfashi', 'status' => 1],
                ['code' => '30939C', 'name' => 'Mani', 'status' => 1],
                ['code' => '30940C', 'name' => 'Mashi', 'status' => 1],
                ['code' => '30941C', 'name' => 'Matazu', 'status' => 1],
                ['code' => '30942C', 'name' => 'Musawa', 'status' => 1],
                ['code' => '30943C', 'name' => 'Rimi', 'status' => 1],
                ['code' => '30944C', 'name' => 'Sabuwa', 'status' => 1],
                ['code' => '30945C', 'name' => 'Safana', 'status' => 1],
                ['code' => '30946C', 'name' => 'Sandamu', 'status' => 1],
                ['code' => '30947C', 'name' => 'Zango', 'status' => 1],
            ];

            // Insert LGAs and keep track of their IDs
            $lgaIds = [];
            foreach ($lgas as $lgaData) {
                $lga = Lga::create($lgaData);
                $lgaIds[$lga->code] = $lga->id;
            }

            // Hardcoded Ward data for Katsina State (example wards for each LGA)
            $wards = [
                // Bakori LGA wards
                ['code' => 'BAK001', 'name' => 'Bakori', 'lga_code' => '30917C', 'status' => 1],
                ['code' => 'BAK002', 'name' => 'Dutsen Kura', 'lga_code' => '30917C', 'status' => 1],
                ['code' => 'BAK003', 'name' => 'Gyara', 'lga_code' => '30917C', 'status' => 1],
                ['code' => 'BAK004', 'name' => 'Kwasabu', 'lga_code' => '30917C', 'status' => 1],
                
                // Batagarawa LGA wards
                ['code' => 'BAT001', 'name' => 'Batagarawa', 'lga_code' => '48330C', 'status' => 1],
                ['code' => 'BAT002', 'name' => 'Doron Dutse', 'lga_code' => '48330C', 'status' => 1],
                ['code' => 'BAT003', 'name' => 'Kwasana', 'lga_code' => '48330C', 'status' => 1],
                ['code' => 'BAT004', 'name' => 'Kandadagu', 'lga_code' => '48330C', 'status' => 1],
                
                // Batsari LGA wards
                ['code' => 'BTS001', 'name' => 'Batsari', 'lga_code' => '30918C', 'status' => 1],
                ['code' => 'BTS002', 'name' => 'Majema', 'lga_code' => '30918C', 'status' => 1],
                ['code' => 'BTS003', 'name' => 'Dutsen Kura', 'lga_code' => '30918C', 'status' => 1],
                ['code' => 'BTS004', 'name' => 'Kwasango', 'lga_code' => '30918C', 'status' => 1],
                
                // Baure LGA wards
                ['code' => 'BAU001', 'name' => 'Baure', 'lga_code' => '48331C', 'status' => 1],
                ['code' => 'BAU002', 'name' => 'Birnin Tudu', 'lga_code' => '48331C', 'status' => 1],
                ['code' => 'BAU003', 'name' => 'Dutsen Kura', 'lga_code' => '48331C', 'status' => 1],
                ['code' => 'BAU004', 'name' => 'Kwasango', 'lga_code' => '48331C', 'status' => 1],
                
                // Bindawa LGA wards
                ['code' => 'BIN001', 'name' => 'Bindawa', 'lga_code' => '30919C', 'status' => 1],
                ['code' => 'BIN002', 'name' => 'Dutsen Kura', 'lga_code' => '30919C', 'status' => 1],
                ['code' => 'BIN003', 'name' => 'Kwasango', 'lga_code' => '30919C', 'status' => 1],
                ['code' => 'BIN004', 'name' => 'Majema', 'lga_code' => '30919C', 'status' => 1],
                
                // Charanchi LGA wards
                ['code' => 'CHA001', 'name' => 'Charanchi', 'lga_code' => '30920C', 'status' => 1],
                ['code' => 'CHA002', 'name' => 'Dutsen Kura', 'lga_code' => '30920C', 'status' => 1],
                ['code' => 'CHA003', 'name' => 'Kwasango', 'lga_code' => '30920C', 'status' => 1],
                ['code' => 'CHA004', 'name' => 'Majema', 'lga_code' => '30920C', 'status' => 1],
                
                // Dandume LGA wards
                ['code' => 'DAN001', 'name' => 'Dandume', 'lga_code' => '30921C', 'status' => 1],
                ['code' => 'DAN002', 'name' => 'Dutsen Kura', 'lga_code' => '30921C', 'status' => 1],
                ['code' => 'DAN003', 'name' => 'Kwasango', 'lga_code' => '30921C', 'status' => 1],
                ['code' => 'DAN004', 'name' => 'Majema', 'lga_code' => '30921C', 'status' => 1],
                
                // Danja LGA wards
                ['code' => 'DJZ001', 'name' => 'Danja', 'lga_code' => '30922C', 'status' => 1],
                ['code' => 'DJZ002', 'name' => 'Dutsen Kura', 'lga_code' => '30922C', 'status' => 1],
                ['code' => 'DJZ003', 'name' => 'Kwasango', 'lga_code' => '30922C', 'status' => 1],
                ['code' => 'DJZ004', 'name' => 'Majema', 'lga_code' => '30922C', 'status' => 1],
                
                // Danmusa LGA wards
                ['code' => 'DMZ001', 'name' => 'Danmusa', 'lga_code' => '48332C', 'status' => 1],
                ['code' => 'DMZ002', 'name' => 'Dutsen Kura', 'lga_code' => '48332C', 'status' => 1],
                ['code' => 'DMZ003', 'name' => 'Kwasango', 'lga_code' => '48332C', 'status' => 1],
                ['code' => 'DMZ004', 'name' => 'Majema', 'lga_code' => '48332C', 'status' => 1],
                
                // Daura LGA wards
                ['code' => 'DAU001', 'name' => 'Daura', 'lga_code' => '30923C', 'status' => 1],
                ['code' => 'DAU002', 'name' => 'Dutsen Kura', 'lga_code' => '30923C', 'status' => 1],
                ['code' => 'DAU003', 'name' => 'Kwasango', 'lga_code' => '30923C', 'status' => 1],
                ['code' => 'DAU004', 'name' => 'Majema', 'lga_code' => '30923C', 'status' => 1],
                
                // Additional wards for other LGAs...
                // Dutsi LGA wards
                ['code' => 'DUT001', 'name' => 'Dutsi', 'lga_code' => '30924C', 'status' => 1],
                ['code' => 'DUT002', 'name' => 'Dutsen Kura', 'lga_code' => '30924C', 'status' => 1],
                ['code' => 'DUT003', 'name' => 'Kwasango', 'lga_code' => '30924C', 'status' => 1],
                ['code' => 'DUT004', 'name' => 'Majema', 'lga_code' => '30924C', 'status' => 1],
                
                // Dutsin Ma LGA wards
                ['code' => 'DUM001', 'name' => 'Dutsin Ma', 'lga_code' => '30925C', 'status' => 1],
                ['code' => 'DUM002', 'name' => 'Dutsen Kura', 'lga_code' => '30925C', 'status' => 1],
                ['code' => 'DUM003', 'name' => 'Kwasango', 'lga_code' => '30925C', 'status' => 1],
                ['code' => 'DUM004', 'name' => 'Majema', 'lga_code' => '30925C', 'status' => 1],
                
                // Faskari LGA wards
                ['code' => 'FAS001', 'name' => 'Faskari', 'lga_code' => '30926C', 'status' => 1],
                ['code' => 'FAS002', 'name' => 'Dutsen Kura', 'lga_code' => '30926C', 'status' => 1],
                ['code' => 'FAS003', 'name' => 'Kwasango', 'lga_code' => '30926C', 'status' => 1],
                ['code' => 'FAS004', 'name' => 'Majema', 'lga_code' => '30926C', 'status' => 1],
                
                // Funtua LGA wards
                ['code' => 'FUN001', 'name' => 'Funtua', 'lga_code' => '30927C', 'status' => 1],
                ['code' => 'FUN002', 'name' => 'Dutsen Kura', 'lga_code' => '30927C', 'status' => 1],
                ['code' => 'FUN003', 'name' => 'Kwasango', 'lga_code' => '30927C', 'status' => 1],
                ['code' => 'FUN004', 'name' => 'Majema', 'lga_code' => '30927C', 'status' => 1],
                
                // Ingawa LGA wards
                ['code' => 'ING001', 'name' => 'Ingawa', 'lga_code' => '30928C', 'status' => 1],
                ['code' => 'ING002', 'name' => 'Dutsen Kura', 'lga_code' => '30928C', 'status' => 1],
                ['code' => 'ING003', 'name' => 'Kwasango', 'lga_code' => '30928C', 'status' => 1],
                ['code' => 'ING004', 'name' => 'Majema', 'lga_code' => '30928C', 'status' => 1],
                
                // Jibia LGA wards
                ['code' => 'JIB001', 'name' => 'Jibia', 'lga_code' => '30929C', 'status' => 1],
                ['code' => 'JIB002', 'name' => 'Dutsen Kura', 'lga_code' => '30929C', 'status' => 1],
                ['code' => 'JIB003', 'name' => 'Kwasango', 'lga_code' => '30929C', 'status' => 1],
                ['code' => 'JIB004', 'name' => 'Majema', 'lga_code' => '30929C', 'status' => 1],
                
                // Kafur LGA wards
                ['code' => 'KAF001', 'name' => 'Kafur', 'lga_code' => '30930C', 'status' => 1],
                ['code' => 'KAF002', 'name' => 'Dutsen Kura', 'lga_code' => '30930C', 'status' => 1],
                ['code' => 'KAF003', 'name' => 'Kwasango', 'lga_code' => '30930C', 'status' => 1],
                ['code' => 'KAF004', 'name' => 'Majema', 'lga_code' => '30930C', 'status' => 1],
                
                // Kaita LGA wards
                ['code' => 'KAI001', 'name' => 'Kaita', 'lga_code' => '30931C', 'status' => 1],
                ['code' => 'KAI002', 'name' => 'Dutsen Kura', 'lga_code' => '30931C', 'status' => 1],
                ['code' => 'KAI003', 'name' => 'Kwasango', 'lga_code' => '30931C', 'status' => 1],
                ['code' => 'KAI004', 'name' => 'Majema', 'lga_code' => '30931C', 'status' => 1],
                
                // Kankara LGA wards
                ['code' => 'KAN001', 'name' => 'Kankara', 'lga_code' => '30932C', 'status' => 1],
                ['code' => 'KAN002', 'name' => 'Dutsen Kura', 'lga_code' => '30932C', 'status' => 1],
                ['code' => 'KAN003', 'name' => 'Kwasango', 'lga_code' => '30932C', 'status' => 1],
                ['code' => 'KAN004', 'name' => 'Majema', 'lga_code' => '30932C', 'status' => 1],
                
                // Kankia LGA wards
                ['code' => 'KNI001', 'name' => 'Kankia', 'lga_code' => '30933C', 'status' => 1],
                ['code' => 'KNI002', 'name' => 'Dutsen Kura', 'lga_code' => '30933C', 'status' => 1],
                ['code' => 'KNI003', 'name' => 'Kwasango', 'lga_code' => '30933C', 'status' => 1],
                ['code' => 'KNI004', 'name' => 'Majema', 'lga_code' => '30933C', 'status' => 1],
                
                // Katsina LGA wards
                ['code' => 'KAT001', 'name' => 'Katsina', 'lga_code' => '30934C', 'status' => 1],
                ['code' => 'KAT002', 'name' => 'Dutsen Kura', 'lga_code' => '30934C', 'status' => 1],
                ['code' => 'KAT003', 'name' => 'Kwasango', 'lga_code' => '30934C', 'status' => 1],
                ['code' => 'KAT004', 'name' => 'Majema', 'lga_code' => '30934C', 'status' => 1],
                
                // Kurfi LGA wards
                ['code' => 'KUR001', 'name' => 'Kurfi', 'lga_code' => '30935C', 'status' => 1],
                ['code' => 'KUR002', 'name' => 'Dutsen Kura', 'lga_code' => '30935C', 'status' => 1],
                ['code' => 'KUR003', 'name' => 'Kwasango', 'lga_code' => '30935C', 'status' => 1],
                ['code' => 'KUR004', 'name' => 'Majema', 'lga_code' => '30935C', 'status' => 1],
                
                // Kusada LGA wards
                ['code' => 'KUS001', 'name' => 'Kusada', 'lga_code' => '30936C', 'status' => 1],
                ['code' => 'KUS002', 'name' => 'Dutsen Kura', 'lga_code' => '30936C', 'status' => 1],
                ['code' => 'KUS003', 'name' => 'Kwasango', 'lga_code' => '30936C', 'status' => 1],
                ['code' => 'KUS004', 'name' => 'Majema', 'lga_code' => '30936C', 'status' => 1],
                
                // Mai Adua LGA wards
                ['code' => 'MAI001', 'name' => 'Mai Adua', 'lga_code' => '30937C', 'status' => 1],
                ['code' => 'MAI002', 'name' => 'Dutsen Kura', 'lga_code' => '30937C', 'status' => 1],
                ['code' => 'MAI003', 'name' => 'Kwasango', 'lga_code' => '30937C', 'status' => 1],
                ['code' => 'MAI004', 'name' => 'Majema', 'lga_code' => '30937C', 'status' => 1],
                
                // Malumfashi LGA wards
                ['code' => 'MAL001', 'name' => 'Malumfashi', 'lga_code' => '30938C', 'status' => 1],
                ['code' => 'MAL002', 'name' => 'Dutsen Kura', 'lga_code' => '30938C', 'status' => 1],
                ['code' => 'MAL003', 'name' => 'Kwasango', 'lga_code' => '30938C', 'status' => 1],
                ['code' => 'MAL004', 'name' => 'Majema', 'lga_code' => '30938C', 'status' => 1],
                
                // Mani LGA wards
                ['code' => 'MAN001', 'name' => 'Mani', 'lga_code' => '30939C', 'status' => 1],
                ['code' => 'MAN002', 'name' => 'Dutsen Kura', 'lga_code' => '30939C', 'status' => 1],
                ['code' => 'MAN003', 'name' => 'Kwasango', 'lga_code' => '30939C', 'status' => 1],
                ['code' => 'MAN004', 'name' => 'Majema', 'lga_code' => '30939C', 'status' => 1],
                
                // Mashi LGA wards
                ['code' => 'MAS001', 'name' => 'Mashi', 'lga_code' => '30940C', 'status' => 1],
                ['code' => 'MAS002', 'name' => 'Dutsen Kura', 'lga_code' => '30940C', 'status' => 1],
                ['code' => 'MAS003', 'name' => 'Kwasango', 'lga_code' => '30940C', 'status' => 1],
                ['code' => 'MAS004', 'name' => 'Majema', 'lga_code' => '30940C', 'status' => 1],
                
                // Matazu LGA wards
                ['code' => 'MAT001', 'name' => 'Matazu', 'lga_code' => '30941C', 'status' => 1],
                ['code' => 'MAT002', 'name' => 'Dutsen Kura', 'lga_code' => '30941C', 'status' => 1],
                ['code' => 'MAT003', 'name' => 'Kwasango', 'lga_code' => '30941C', 'status' => 1],
                ['code' => 'MAT004', 'name' => 'Majema', 'lga_code' => '30941C', 'status' => 1],
                
                // Musawa LGA wards
                ['code' => 'MUS001', 'name' => 'Musawa', 'lga_code' => '30942C', 'status' => 1],
                ['code' => 'MUS002', 'name' => 'Dutsen Kura', 'lga_code' => '30942C', 'status' => 1],
                ['code' => 'MUS003', 'name' => 'Kwasango', 'lga_code' => '30942C', 'status' => 1],
                ['code' => 'MUS004', 'name' => 'Majema', 'lga_code' => '30942C', 'status' => 1],
                
                // Rimi LGA wards
                ['code' => 'RIM001', 'name' => 'Rimi', 'lga_code' => '30943C', 'status' => 1],
                ['code' => 'RIM002', 'name' => 'Dutsen Kura', 'lga_code' => '30943C', 'status' => 1],
                ['code' => 'RIM003', 'name' => 'Kwasango', 'lga_code' => '30943C', 'status' => 1],
                ['code' => 'RIM004', 'name' => 'Majema', 'lga_code' => '30943C', 'status' => 1],
                
                // Sabuwa LGA wards
                ['code' => 'SAB001', 'name' => 'Sabuwa', 'lga_code' => '30944C', 'status' => 1],
                ['code' => 'SAB002', 'name' => 'Dutsen Kura', 'lga_code' => '30944C', 'status' => 1],
                ['code' => 'SAB003', 'name' => 'Kwasango', 'lga_code' => '30944C', 'status' => 1],
                ['code' => 'SAB004', 'name' => 'Majema', 'lga_code' => '30944C', 'status' => 1],
                
                // Safana LGA wards
                ['code' => 'SAF001', 'name' => 'Safana', 'lga_code' => '30945C', 'status' => 1],
                ['code' => 'SAF002', 'name' => 'Dutsen Kura', 'lga_code' => '30945C', 'status' => 1],
                ['code' => 'SAF003', 'name' => 'Kwasango', 'lga_code' => '30945C', 'status' => 1],
                ['code' => 'SAF004', 'name' => 'Majema', 'lga_code' => '30945C', 'status' => 1],
                
                // Sandamu LGA wards
                ['code' => 'SAN001', 'name' => 'Sandamu', 'lga_code' => '30946C', 'status' => 1],
                ['code' => 'SAN002', 'name' => 'Dutsen Kura', 'lga_code' => '30946C', 'status' => 1],
                ['code' => 'SAN003', 'name' => 'Kwasango', 'lga_code' => '30946C', 'status' => 1],
                ['code' => 'SAN004', 'name' => 'Majema', 'lga_code' => '30946C', 'status' => 1],
                
                // Zango LGA wards
                ['code' => 'ZAN001', 'name' => 'Zango', 'lga_code' => '30947C', 'status' => 1],
                ['code' => 'ZAN002', 'name' => 'Dutsen Kura', 'lga_code' => '30947C', 'status' => 1],
                ['code' => 'ZAN003', 'name' => 'Kwasango', 'lga_code' => '30947C', 'status' => 1],
                ['code' => 'ZAN004', 'name' => 'Majema', 'lga_code' => '30947C', 'status' => 1],
            ];

            // Insert Wards with proper LGA IDs
            foreach ($wards as $ward) {
                $wardData = [
                    'code' => $ward['code'],
                    'name' => $ward['name'],
                    'lga_id' => $lgaIds[$ward['lga_code']],
                    'status' => $ward['status']
                ];
                Ward::create($wardData);
            }

            $this->command->info('LGA and Ward data seeded successfully!');
        } catch (\Exception $e) {
            // Re-enable foreign key constraints in case of error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->error('Error importing data: ' . $e->getMessage());
            throw $e; // Re-throw to see the full error
        }
    }
}