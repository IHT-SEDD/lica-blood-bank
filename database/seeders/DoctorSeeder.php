<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = public_path('assets/data/doctors.json');
        if (!File::exists($path)) {
            $this->command->error("File doctors.json tidak ditemukan");
            return;
        }

        $json = File::get($path);
        $doctors = json_decode($json, true);
        if (!$doctors || !is_array($doctors)) {
            $this->command->error("Format JSON tidak valid");
            return;
        }

        $insertData = [];

        foreach ($doctors as $doctor) {
            $exists = Doctor::where('general_code', $doctor['general_code'])->exists();
            if ($exists) {
                continue;
            }

            $insertData[] = [
                'public_id' => (string) Str::uuid(),
                'name' => $doctor['name'],
                'general_code' => $doctor['general_code'],
                'no_telephone' => $doctor['no_telephone'],
                'is_active' => $doctor['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($insertData)) {
            Doctor::insert($insertData);
            $this->command->info(count($insertData) . ' doctor berhasil diinsert');
        } else {
            $this->command->info('Semua data doctor sudah ada');
        }
    }
}
