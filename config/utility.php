<?php

return [

    // ---------- A ----------
    'add-incoming-stock-method' => [
        'type' => 'enum',
    ],

    // ---------- B ----------
    'bag-number' => [
        'model' => App\Models\IncomingBloodDetail::class,
        'label' => 'bag_number',
        'with' => [],
    ],
    'blood-stock' => [
        'model' => App\Models\BloodStock::class,
        'label' => 'bag_number',
        'with' => [],
        'conditions' => [
            [
                'field' => 'blood_status',
                'value' => 'available',
            ],
        ],
    ],
    'blood-transfusion-detail' => [
        'model' => App\Models\BloodTransfusionDetail::class,
        'label' => '',
        'with' => ['bloodTransfusion', 'bloodTransfusionDetailTests'],
    ],
    'bag-number-by-po' => [
        'model' => App\Models\IncomingBloodDetail::class,
        'label' => 'bag_number',
        'with' => ['incomingBloods'],
        'conditions' => [
            [
                'field' => 'incomingBloods.po_number',
                'operator' => 'whereHas',
                'relation' => 'incomingBloods',
                'value_field' => 'po_number',
            ],
            [
                'field' => 'ready_at',
                'operator' => 'whereNull',
            ],
        ],
    ],
    'blood-component' => [
        'type' => 'enum',
    ],
    'blood-group' => [
        'type' => 'enum',
    ],
    'blood-pack' => [
        'model' => App\Models\BloodPack::class,
        'label' => 'label',
        'with' => [],
    ],
    'blood-rhesus' => [
        'type' => 'enum',
    ],
    'blood-status' => [
        'type' => 'enum',
    ],
    'blood-stock-status' => [
        'type' => 'enum',
    ],
    'blood-transfusion-status' => [
        'type' => 'enum',
    ],

    // ---------- D ----------
    'doctor' => [
        'model' => App\Models\Doctor::class,
        'label' => 'name',
        'with' => [],
    ],
    'dct-value' => [
        'type' => 'enum',
    ],

    // ---------- I ----------
    'incoming-stock-status' => [
        'type' => 'enum',
    ],
    'insurance' => [
        'model' => App\Models\Insurance::class,
        'label' => 'name',
        'with' => [],
    ],

    // ---------- L ----------
    'level-reaction' => [
        'type' => 'enum',
    ],

    // ---------- O ----------
    'order-status' => [
        'type' => 'enum',
    ],

    // ---------- P ----------
    'package' => [
        'model' => App\Models\Package::class,
        'label' => 'name',
        'with' => [],
    ],
    'patient' => [
        'model' => App\Models\Patient::class,
        'label' => 'name',
        'with' => [],
    ],
    'purchase-order' => [
        'model' => App\Models\OrderBlood::class,
        'label' => 'po_number',
        'with' => [],
        'conditions' => [
            [
                'field' => 'status',
                'operator' => 'not_in',
                'value' => ['draft', 'done', 'deleted', 'cancelled'],
            ],
        ],
    ],
    'purchase-order-registered' => [
        'model' => App\Models\IncomingBlood::class,
        'label' => 'po_number',
        'with' => [],
        'conditions' => [
            [
                'field' => 'status',
                'operator' => 'not_in',
                'value' => ['stock_ready', 'deleted'],
            ],
        ],
    ],

    // ---------- R ----------
    'rack-type' => [
        'type' => 'enum',
    ],
    'relation-type' => [
        'type' => 'enum',
    ],
    'role' => [
        'model' => Spatie\Permission\Models\Role::class,
        'label' => 'name',
        'with' => [],
    ],
    'room' => [
        'model' => App\Models\Room::class,
        'label' => 'name',
        'with' => [],
    ],
    'result-test' => [
        'type' => 'enum',
    ],

    // ---------- S ----------
    'storage' => [
        'model' => App\Models\Storage::class,
        'label' => 'name',
        'with' => [],
    ],
    'storage-rack' => [
        'model' => App\Models\StorageRack::class,
        'label' => ['storages.name', 'name'], // Array, text display pada tom-select akan sesuai urutan array ini
        'label_separator' => ' - ', // Opsional, defaultnya '-'
        'with' => ['storages'],
    ],

    // ---------- T ----------
    'test' => [
        'model' => App\Models\Test::class,
        'label' => 'name',
        'with' => [],
    ],

    // ---------- U ----------
    'user' => [
        'model' => App\Models\User::class,
        'label' => 'name',
        'with' => ['roles'],
    ],

    // ---------- V ----------
    'vendor' => [
        'model' => App\Models\Vendor::class,
        'label' => 'name',
        'with' => [],
    ],
];
