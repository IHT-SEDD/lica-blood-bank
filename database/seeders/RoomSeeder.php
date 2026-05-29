<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = public_path('assets/data/rooms.json');
        if (!File::exists($path)) {
            $this->command->error("File rooms.json tidak ditemukan");
            return;
        }

        $json = File::get($path);
        $rooms = json_decode($json, true);
        if (!$rooms || !is_array($rooms)) {
            $this->command->error("Format JSON tidak valid");
            return;
        }

        $insertData = [];

        foreach ($rooms as $room) {
            $exists = Room::where('general_code', $room['general_code'])->exists();
            if ($exists) {
                continue;
            }

            $insertData[] = [
                'public_id' => (string) Str::uuid(),
                'name' => $room['room'],
                'type' => $room['type'],
                'class' => $room['class'],
                'general_code' => $room['general_code'],
                'is_active' => $room['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($insertData)) {
            Room::insert($insertData);
            $this->command->info(count($insertData) . ' room berhasil diinsert');
        } else {
            $this->command->info('Semua data room sudah ada');
        }
    }
}
