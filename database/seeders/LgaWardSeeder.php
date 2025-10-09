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
            // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Clear existing LGA and Ward data (in the right order to respect foreign key constraints)
            DB::table('wards')->delete();
            DB::table('lgas')->delete();
            
// DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Hardcoded Katsina State LGA data
            $lgas = [
                ['code' => '30917C', 'name' => 'Bakori', 'status' => 'approved'],
                ['code' => '48330C', 'name' => 'Batagarawa', 'status' => 'approved'],
                ['code' => '30918C', 'name' => 'Batsari', 'status' => 'approved'],
                ['code' => '48331C', 'name' => 'Baure', 'status' => 'approved'],
                ['code' => '30919C', 'name' => 'Bindawa', 'status' => 'approved'],
                ['code' => '30920C', 'name' => 'Charanchi', 'status' => 'approved'],
                ['code' => '30921C', 'name' => 'Dandume', 'status' => 'approved'],
                ['code' => '30922C', 'name' => 'Danja', 'status' => 'approved'],
                ['code' => '48332C', 'name' => 'Danmusa', 'status' => 'approved'],
                ['code' => '30923C', 'name' => 'Daura', 'status' => 'approved'],
                ['code' => '30924C', 'name' => 'Dutsi', 'status' => 'approved'],
                ['code' => '30925C', 'name' => 'Dutsin Ma', 'status' => 'approved'],
                ['code' => '30926C', 'name' => 'Faskari', 'status' => 'approved'],
                ['code' => '30927C', 'name' => 'Funtua', 'status' => 'approved'],
                ['code' => '30928C', 'name' => 'Ingawa', 'status' => 'approved'],
                ['code' => '30929C', 'name' => 'Jibia', 'status' => 'approved'],
                ['code' => '30930C', 'name' => 'Kafur', 'status' => 'approved'],
                ['code' => '30931C', 'name' => 'Kaita', 'status' => 'approved'],
                ['code' => '30932C', 'name' => 'Kankara', 'status' => 'approved'],
                ['code' => '30933C', 'name' => 'Kankia', 'status' => 'approved'],
                ['code' => '30934C', 'name' => 'Katsina', 'status' => 'approved'],
                ['code' => '30935C', 'name' => 'Kurfi', 'status' => 'approved'],
                ['code' => '30936C', 'name' => 'Kusada', 'status' => 'approved'],
                ['code' => '30937C', 'name' => 'Mai Adua', 'status' => 'approved'],
                ['code' => '30938C', 'name' => 'Malumfashi', 'status' => 'approved'],
                ['code' => '30939C', 'name' => 'Mani', 'status' => 'approved'],
                ['code' => '30940C', 'name' => 'Mashi', 'status' => 'approved'],
                ['code' => '30941C', 'name' => 'Matazu', 'status' => 'approved'],
                ['code' => '30942C', 'name' => 'Musawa', 'status' => 'approved'],
                ['code' => '30943C', 'name' => 'Rimi', 'status' => 'approved'],
                ['code' => '30944C', 'name' => 'Sabuwa', 'status' => 'approved'],
                ['code' => '30945C', 'name' => 'Safana', 'status' => 'approved'],
                ['code' => '30946C', 'name' => 'Sandamu', 'status' => 'approved'],
                ['code' => '30947C', 'name' => 'Zango', 'status' => 'approved'],
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
                ['code' => 'BAK001', 'name' => 'Bakori', 'lga_code' => '30917C', 'status' => 'approved'],
                ['code' => 'BAK002', 'name' => 'Dutsen Kura', 'lga_code' => '30917C', 'status' => 'approved'],
                ['code' => 'BAK003', 'name' => 'Gyara', 'lga_code' => '30917C', 'status' => 'approved'],
                ['code' => 'BAK004', 'name' => 'Kwasabu', 'lga_code' => '30917C', 'status' => 'approved'],
                
                // Batagarawa LGA wards
                ['code' => 'BAT001', 'name' => 'Batagarawa', 'lga_code' => '48330C', 'status' => 'approved'],
                ['code' => 'BAT002', 'name' => 'Doron Dutse', 'lga_code' => '48330C', 'status' => 'approved'],
                ['code' => 'BAT003', 'name' => 'Kwasana', 'lga_code' => '48330C', 'status' => 'approved'],
                ['code' => 'BAT004', 'name' => 'Kandadagu', 'lga_code' => '48330C', 'status' => 'approved'],
                
                // Batsari LGA wards
                ['code' => 'BTS001', 'name' => 'Batsari', 'lga_code' => '30918C', 'status' => 'approved'],
                ['code' => 'BTS002', 'name' => 'Majema', 'lga_code' => '30918C', 'status' => 'approved'],
                ['code' => 'BTS003', 'name' => 'Dutsen Kura', 'lga_code' => '30918C', 'status' => 'approved'],
                ['code' => 'BTS004', 'name' => 'Kwasango', 'lga_code' => '30918C', 'status' => 'approved'],
                
                // Baure LGA wards
                ['code' => 'BAU001', 'name' => 'Baure', 'lga_code' => '48331C', 'status' => 'approved'],
                ['code' => 'BAU002', 'name' => 'Birnin Tudu', 'lga_code' => '48331C', 'status' => 'approved'],
                ['code' => 'BAU003', 'name' => 'Dutsen Kura', 'lga_code' => '48331C', 'status' => 'approved'],
                ['code' => 'BAU004', 'name' => 'Kwasango', 'lga_code' => '48331C', 'status' => 'approved'],
                
                // Bindawa LGA wards
                ['code' => 'BIN001', 'name' => 'Bindawa', 'lga_code' => '30919C', 'status' => 'approved'],
                ['code' => 'BIN002', 'name' => 'Dutsen Kura', 'lga_code' => '30919C', 'status' => 'approved'],
                ['code' => 'BIN003', 'name' => 'Kwasango', 'lga_code' => '30919C', 'status' => 'approved'],
                ['code' => 'BIN004', 'name' => 'Majema', 'lga_code' => '30919C', 'status' => 'approved'],
                
                // Charanchi LGA wards
                ['code' => 'CHA001', 'name' => 'Charanchi', 'lga_code' => '30920C', 'status' => 'approved'],
                ['code' => 'CHA002', 'name' => 'Dutsen Kura', 'lga_code' => '30920C', 'status' => 'approved'],
                ['code' => 'CHA003', 'name' => 'Kwasango', 'lga_code' => '30920C', 'status' => 'approved'],
                ['code' => 'CHA004', 'name' => 'Majema', 'lga_code' => '30920C', 'status' => 'approved'],
                
                // Dandume LGA wards
                ['code' => 'DAN001', 'name' => 'Dandume', 'lga_code' => '30921C', 'status' => 'approved'],
                ['code' => 'DAN002', 'name' => 'Dutsen Kura', 'lga_code' => '30921C', 'status' => 'approved'],
                ['code' => 'DAN003', 'name' => 'Kwasango', 'lga_code' => '30921C', 'status' => 'approved'],
                ['code' => 'DAN004', 'name' => 'Majema', 'lga_code' => '30921C', 'status' => 'approved'],
                
                // Danja LGA wards
                ['code' => 'DJZ001', 'name' => 'Danja', 'lga_code' => '30922C', 'status' => 'approved'],
                ['code' => 'DJZ002', 'name' => 'Dutsen Kura', 'lga_code' => '30922C', 'status' => 'approved'],
                ['code' => 'DJZ003', 'name' => 'Kwasango', 'lga_code' => '30922C', 'status' => 'approved'],
                ['code' => 'DJZ004', 'name' => 'Majema', 'lga_code' => '30922C', 'status' => 'approved'],
                
                // Danmusa LGA wards
                ['code' => 'DMZ001', 'name' => 'Danmusa', 'lga_code' => '48332C', 'status' => 'approved'],
                ['code' => 'DMZ002', 'name' => 'Dutsen Kura', 'lga_code' => '48332C', 'status' => 'approved'],
                ['code' => 'DMZ003', 'name' => 'Kwasango', 'lga_code' => '48332C', 'status' => 'approved'],
                ['code' => 'DMZ004', 'name' => 'Majema', 'lga_code' => '48332C', 'status' => 'approved'],
                
                // Daura LGA wards
                ['code' => 'DAU001', 'name' => 'Daura', 'lga_code' => '30923C', 'status' => 'approved'],
                ['code' => 'DAU002', 'name' => 'Dutsen Kura', 'lga_code' => '30923C', 'status' => 'approved'],
                ['code' => 'DAU003', 'name' => 'Kwasango', 'lga_code' => '30923C', 'status' => 'approved'],
                ['code' => 'DAU004', 'name' => 'Majema', 'lga_code' => '30923C', 'status' => 'approved'],
                
                // Additional wards for other LGAs...
                // Dutsi LGA wards
                ['code' => 'DUT001', 'name' => 'Dutsi', 'lga_code' => '30924C', 'status' => 'approved'],
                ['code' => 'DUT002', 'name' => 'Dutsen Kura', 'lga_code' => '30924C', 'status' => 'approved'],
                ['code' => 'DUT003', 'name' => 'Kwasango', 'lga_code' => '30924C', 'status' => 'approved'],
                ['code' => 'DUT004', 'name' => 'Majema', 'lga_code' => '30924C', 'status' => 'approved'],
                
                // Dutsin Ma LGA wards
                ['code' => 'DUM001', 'name' => 'Dutsin Ma', 'lga_code' => '30925C', 'status' => 'approved'],
                ['code' => 'DUM002', 'name' => 'Dutsen Kura', 'lga_code' => '30925C', 'status' => 'approved'],
                ['code' => 'DUM003', 'name' => 'Kwasango', 'lga_code' => '30925C', 'status' => 'approved'],
                ['code' => 'DUM004', 'name' => 'Majema', 'lga_code' => '30925C', 'status' => 'approved'],
                
                // Faskari LGA wards
                ['code' => 'FAS001', 'name' => 'Faskari', 'lga_code' => '30926C', 'status' => 'approved'],
                ['code' => 'FAS002', 'name' => 'Dutsen Kura', 'lga_code' => '30926C', 'status' => 'approved'],
                ['code' => 'FAS003', 'name' => 'Kwasango', 'lga_code' => '30926C', 'status' => 'approved'],
                ['code' => 'FAS004', 'name' => 'Majema', 'lga_code' => '30926C', 'status' => 'approved'],
                
                // Funtua LGA wards
                ['code' => 'FUN001', 'name' => 'Funtua', 'lga_code' => '30927C', 'status' => 'approved'],
                ['code' => 'FUN002', 'name' => 'Dutsen Kura', 'lga_code' => '30927C', 'status' => 'approved'],
                ['code' => 'FUN003', 'name' => 'Kwasango', 'lga_code' => '30927C', 'status' => 'approved'],
                ['code' => 'FUN004', 'name' => 'Majema', 'lga_code' => '30927C', 'status' => 'approved'],
                
                // Ingawa LGA wards
                ['code' => 'ING001', 'name' => 'Ingawa', 'lga_code' => '30928C', 'status' => 'approved'],
                ['code' => 'ING002', 'name' => 'Dutsen Kura', 'lga_code' => '30928C', 'status' => 'approved'],
                ['code' => 'ING003', 'name' => 'Kwasango', 'lga_code' => '30928C', 'status' => 'approved'],
                ['code' => 'ING004', 'name' => 'Majema', 'lga_code' => '30928C', 'status' => 'approved'],
                
                // Jibia LGA wards
                ['code' => 'JIB001', 'name' => 'Jibia', 'lga_code' => '30929C', 'status' => 'approved'],
                ['code' => 'JIB002', 'name' => 'Dutsen Kura', 'lga_code' => '30929C', 'status' => 'approved'],
                ['code' => 'JIB003', 'name' => 'Kwasango', 'lga_code' => '30929C', 'status' => 'approved'],
                ['code' => 'JIB004', 'name' => 'Majema', 'lga_code' => '30929C', 'status' => 'approved'],
                
                // Kafur LGA wards
                ['code' => 'KAF001', 'name' => 'Kafur', 'lga_code' => '30930C', 'status' => 'approved'],
                ['code' => 'KAF002', 'name' => 'Dutsen Kura', 'lga_code' => '30930C', 'status' => 'approved'],
                ['code' => 'KAF003', 'name' => 'Kwasango', 'lga_code' => '30930C', 'status' => 'approved'],
                ['code' => 'KAF004', 'name' => 'Majema', 'lga_code' => '30930C', 'status' => 'approved'],
                
                // Kaita LGA wards
                ['code' => 'KAI001', 'name' => 'Kaita', 'lga_code' => '30931C', 'status' => 'approved'],
                ['code' => 'KAI002', 'name' => 'Dutsen Kura', 'lga_code' => '30931C', 'status' => 'approved'],
                ['code' => 'KAI003', 'name' => 'Kwasango', 'lga_code' => '30931C', 'status' => 'approved'],
                ['code' => 'KAI004', 'name' => 'Majema', 'lga_code' => '30931C', 'status' => 'approved'],
                
                // Kankara LGA wards
                ['code' => 'KAN001', 'name' => 'Kankara', 'lga_code' => '30932C', 'status' => 'approved'],
                ['code' => 'KAN002', 'name' => 'Dutsen Kura', 'lga_code' => '30932C', 'status' => 'approved'],
                ['code' => 'KAN003', 'name' => 'Kwasango', 'lga_code' => '30932C', 'status' => 'approved'],
                ['code' => 'KAN004', 'name' => 'Majema', 'lga_code' => '30932C', 'status' => 'approved'],
                
                // Kankia LGA wards
                ['code' => 'KNI001', 'name' => 'Kankia', 'lga_code' => '30933C', 'status' => 'approved'],
                ['code' => 'KNI002', 'name' => 'Dutsen Kura', 'lga_code' => '30933C', 'status' => 'approved'],
                ['code' => 'KNI003', 'name' => 'Kwasango', 'lga_code' => '30933C', 'status' => 'approved'],
                ['code' => 'KNI004', 'name' => 'Majema', 'lga_code' => '30933C', 'status' => 'approved'],
                
                // Katsina LGA wards
                ['code' => 'KAT001', 'name' => 'Katsina', 'lga_code' => '30934C', 'status' => 'approved'],
                ['code' => 'KAT002', 'name' => 'Dutsen Kura', 'lga_code' => '30934C', 'status' => 'approved'],
                ['code' => 'KAT003', 'name' => 'Kwasango', 'lga_code' => '30934C', 'status' => 'approved'],
                ['code' => 'KAT004', 'name' => 'Majema', 'lga_code' => '30934C', 'status' => 'approved'],
                
                // Kurfi LGA wards
                ['code' => 'KUR001', 'name' => 'Kurfi', 'lga_code' => '30935C', 'status' => 'approved'],
                ['code' => 'KUR002', 'name' => 'Dutsen Kura', 'lga_code' => '30935C', 'status' => 'approved'],
                ['code' => 'KUR003', 'name' => 'Kwasango', 'lga_code' => '30935C', 'status' => 'approved'],
                ['code' => 'KUR004', 'name' => 'Majema', 'lga_code' => '30935C', 'status' => 'approved'],
                
                // Kusada LGA wards
                ['code' => 'KUS001', 'name' => 'Kusada', 'lga_code' => '30936C', 'status' => 'approved'],
                ['code' => 'KUS002', 'name' => 'Dutsen Kura', 'lga_code' => '30936C', 'status' => 'approved'],
                ['code' => 'KUS003', 'name' => 'Kwasango', 'lga_code' => '30936C', 'status' => 'approved'],
                ['code' => 'KUS004', 'name' => 'Majema', 'lga_code' => '30936C', 'status' => 'approved'],
                
                // Mai Adua LGA wards
                ['code' => 'MAI001', 'name' => 'Mai Adua', 'lga_code' => '30937C', 'status' => 'approved'],
                ['code' => 'MAI002', 'name' => 'Dutsen Kura', 'lga_code' => '30937C', 'status' => 'approved'],
                ['code' => 'MAI003', 'name' => 'Kwasango', 'lga_code' => '30937C', 'status' => 'approved'],
                ['code' => 'MAI004', 'name' => 'Majema', 'lga_code' => '30937C', 'status' => 'approved'],
                
                // Malumfashi LGA wards
                ['code' => 'MAL001', 'name' => 'Malumfashi', 'lga_code' => '30938C', 'status' => 'approved'],
                ['code' => 'MAL002', 'name' => 'Dutsen Kura', 'lga_code' => '30938C', 'status' => 'approved'],
                ['code' => 'MAL003', 'name' => 'Kwasango', 'lga_code' => '30938C', 'status' => 'approved'],
                ['code' => 'MAL004', 'name' => 'Majema', 'lga_code' => '30938C', 'status' => 'approved'],
                
                // Mani LGA wards
                ['code' => 'MAN001', 'name' => 'Mani', 'lga_code' => '30939C', 'status' => 'approved'],
                ['code' => 'MAN002', 'name' => 'Dutsen Kura', 'lga_code' => '30939C', 'status' => 'approved'],
                ['code' => 'MAN003', 'name' => 'Kwasango', 'lga_code' => '30939C', 'status' => 'approved'],
                ['code' => 'MAN004', 'name' => 'Majema', 'lga_code' => '30939C', 'status' => 'approved'],
                
                // Mashi LGA wards
                ['code' => 'MAS001', 'name' => 'Mashi', 'lga_code' => '30940C', 'status' => 'approved'],
                ['code' => 'MAS002', 'name' => 'Dutsen Kura', 'lga_code' => '30940C', 'status' => 'approved'],
                ['code' => 'MAS003', 'name' => 'Kwasango', 'lga_code' => '30940C', 'status' => 'approved'],
                ['code' => 'MAS004', 'name' => 'Majema', 'lga_code' => '30940C', 'status' => 'approved'],
                
                // Matazu LGA wards
                ['code' => 'MAT001', 'name' => 'Matazu', 'lga_code' => '30941C', 'status' => 'approved'],
                ['code' => 'MAT002', 'name' => 'Dutsen Kura', 'lga_code' => '30941C', 'status' => 'approved'],
                ['code' => 'MAT003', 'name' => 'Kwasango', 'lga_code' => '30941C', 'status' => 'approved'],
                ['code' => 'MAT004', 'name' => 'Majema', 'lga_code' => '30941C', 'status' => 'approved'],
                
                // Musawa LGA wards
                ['code' => 'MUS001', 'name' => 'Musawa', 'lga_code' => '30942C', 'status' => 'approved'],
                ['code' => 'MUS002', 'name' => 'Dutsen Kura', 'lga_code' => '30942C', 'status' => 'approved'],
                ['code' => 'MUS003', 'name' => 'Kwasango', 'lga_code' => '30942C', 'status' => 'approved'],
                ['code' => 'MUS004', 'name' => 'Majema', 'lga_code' => '30942C', 'status' => 'approved'],
                
                // Rimi LGA wards
                ['code' => 'RIM001', 'name' => 'Rimi', 'lga_code' => '30943C', 'status' => 'approved'],
                ['code' => 'RIM002', 'name' => 'Dutsen Kura', 'lga_code' => '30943C', 'status' => 'approved'],
                ['code' => 'RIM003', 'name' => 'Kwasango', 'lga_code' => '30943C', 'status' => 'approved'],
                ['code' => 'RIM004', 'name' => 'Majema', 'lga_code' => '30943C', 'status' => 'approved'],
                
                // Sabuwa LGA wards
                ['code' => 'SAB001', 'name' => 'Sabuwa', 'lga_code' => '30944C', 'status' => 'approved'],
                ['code' => 'SAB002', 'name' => 'Dutsen Kura', 'lga_code' => '30944C', 'status' => 'approved'],
                ['code' => 'SAB003', 'name' => 'Kwasango', 'lga_code' => '30944C', 'status' => 'approved'],
                ['code' => 'SAB004', 'name' => 'Majema', 'lga_code' => '30944C', 'status' => 'approved'],
                
                // Safana LGA wards
                ['code' => 'SAF001', 'name' => 'Safana', 'lga_code' => '30945C', 'status' => 'approved'],
                ['code' => 'SAF002', 'name' => 'Dutsen Kura', 'lga_code' => '30945C', 'status' => 'approved'],
                ['code' => 'SAF003', 'name' => 'Kwasango', 'lga_code' => '30945C', 'status' => 'approved'],
                ['code' => 'SAF004', 'name' => 'Majema', 'lga_code' => '30945C', 'status' => 'approved'],
                
                // Sandamu LGA wards
                ['code' => 'SAN001', 'name' => 'Sandamu', 'lga_code' => '30946C', 'status' => 'approved'],
                ['code' => 'SAN002', 'name' => 'Dutsen Kura', 'lga_code' => '30946C', 'status' => 'approved'],
                ['code' => 'SAN003', 'name' => 'Kwasango', 'lga_code' => '30946C', 'status' => 'approved'],
                ['code' => 'SAN004', 'name' => 'Majema', 'lga_code' => '30946C', 'status' => 'approved'],
                
                // Zango LGA wards
                ['code' => 'ZAN001', 'name' => 'Zango', 'lga_code' => '30947C', 'status' => 'approved'],
                ['code' => 'ZAN002', 'name' => 'Dutsen Kura', 'lga_code' => '30947C', 'status' => 'approved'],
                ['code' => 'ZAN003', 'name' => 'Kwasango', 'lga_code' => '30947C', 'status' => 'approved'],
                ['code' => 'ZAN004', 'name' => 'Majema', 'lga_code' => '30947C', 'status' => 'approved'],
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