<?php

namespace Database\Seeders;

use App\Models\Insurance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = public_path('assets/data/insurances.json');
        if (!File::exists($path)) {
            $this->command->error("File insurances.json tidak ditemukan");
            return;
        }

        $json = File::get($path);
        $insurances = json_decode($json, true);
        if (!$insurances || !is_array($insurances)) {
            $this->command->error("Format JSON tidak valid");
            return;
        }

        $insertData = [];

        foreach ($insurances as $insurance) {
            $exists = Insurance::where('general_code', $insurance['general_code'])->exists();
            if ($exists) {
                continue;
            }

            $insertData[] = [
                'public_id' => (string) Str::uuid(),
                'name' => $insurance['name'],
                'is_active' => $insurance['is_active'],
                'general_code' => $insurance['general_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($insertData)) {
            Insurance::insert($insertData);
            $this->command->info(count($insertData) . ' insurance berhasil diinsert');
        } else {
            $this->command->info('Semua data insurance sudah ada');
        }
    }
}
