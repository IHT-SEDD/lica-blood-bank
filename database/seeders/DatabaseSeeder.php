<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SuperAdminSeeder::class,
            BloodPackSeeder::class,
            StorageAndRackSeeder::class,
            VendorSeeeder::class,
            RoomSeeder::class,
            InsuranceSeeder::class,
            DoctorSeeder::class,
            TestSeeder::class,
        ]);
    }
}
