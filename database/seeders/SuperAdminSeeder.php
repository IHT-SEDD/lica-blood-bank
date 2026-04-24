<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'superadmin@licabloodbank.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => 'password',
            ]
        );

        $user->syncRoles(['superadmin']);
    }
}
