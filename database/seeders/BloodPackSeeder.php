<?php

namespace Database\Seeders;

use App\Models\BloodPack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BloodPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodGroups = [
            'A',
            'AB',
            'O',
            'B',
        ];

        $bloodRhesus = ['+', '-'];

        $bloodComponents = [
            'CRYO',
            'WB',
            'PRC',
            'TC',
            'FFP',
            'WRC',
        ];

        foreach ($bloodGroups as $group) {
            foreach ($bloodRhesus as $rhesus) {
                foreach ($bloodComponents as $component) {

                    BloodPack::firstOrCreate(
                        [
                            // unique checker
                            'blood_group' => $group,
                            'blood_rhesus' => $rhesus,
                            'blood_component' => $component,
                        ],
                        [
                            // inserted only if not exists
                            'warning_quantity' => 10,
                            'danger_quantity' => 5,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
