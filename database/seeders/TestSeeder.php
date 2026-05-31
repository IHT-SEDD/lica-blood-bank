<?php

namespace Database\Seeders;

use App\Models\Test;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testNames = [
            'Mayor',
            'Minor',
            'Auto Control',
        ];

        foreach ($testNames as $testName) {
            Test::firstOrCreate(
                [
                    'name' => $testName,
                ],
                [
                    'is_active' => true,
                ]
            );
        }
    }
}
