<?php

namespace Database\Seeders;

use App\Models\Storage;
use App\Models\StorageRack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StorageAndRackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $storages = [
            'Freezer 1',
            'Freezer 2',
            'Freezer 3',
            'Freezer 4',
        ];

        $storageRacks = [
            'Rack A',
            'Rack B',
            'Rack O',
            'Rack AB',
            'Rack Reagent',
        ];

        foreach ($storages as $storage) {

            $insertedStorage = Storage::firstOrCreate(
                [
                    'name' => $storage,
                ],
                [
                    'model' => null,
                    'serial_number' => null,
                    'manufacturer' => null,
                    'rack_capacity' => 5,
                    'is_active' => true,
                ]
            );

            // insert racks for each storage
            foreach ($storageRacks as $storageRack) {

                StorageRack::firstOrCreate(
                    [
                        'storage_id' => $insertedStorage->id,
                        'name' => $storageRack,
                    ],
                    [
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
