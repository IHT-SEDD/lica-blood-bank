<?php

return [
    'add-incoming-stock-method' => [
        'type' => 'enum',
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

    'bag-number' => [
        'model' => App\Models\IncomingBloodDetail::class,
        'label' => 'bag_number',
        'with' => [],
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

    'doctor' => [
        'model' => App\Models\Doctor::class,
        'label' => 'name',
        'with' => [],
    ],

    'incoming-stock-status' => [
        'type' => 'enum',
    ],

    'insurance' => [
        'model' => App\Models\Insurance::class,
        'label' => 'name',
        'with' => [],
    ],
    'insurance' => [
    'model' => App\Models\Insurance::class,
    'label' => 'name',
    'with' => [],
    ],

    'room' => [
    'model' => App\Models\Room::class,
    'label' => 'name',
    'with' => [],
    ],
    'order-status' => [
        'type' => 'enum',
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
            ['field' => 'status', 'operator' => 'not_in', 'value' => ['draft', 'done', 'deleted', 'cancelled']],
        ],
    ],

    'purchase-order-registered' => [
        'model' => App\Models\IncomingBlood::class,
        'label' => 'po_number',
        'with' => [],
        'conditions' => [
            ['field' => 'status', 'operator' => 'not_in', 'value' => ['stock_ready', 'deleted']],
        ],
    ],

    'relation-type' => [
        'type' => 'enum',
    ],

    'role' => [
        'model' => Spatie\Permission\Models\Role::class,
        'label' => 'name',
        'with' => [],
    ],

    'storage' => [
        'model' => App\Models\Storage::class,
        'label' => 'name',
        'with' => [],
    ],

    'storage-rack' => [
        'model' => App\Models\StorageRack::class,
        'label' => 'name',
        'with' => ['storages'],
    ],

    'user' => [
        'model' => App\Models\User::class,
        'label' => 'name',
        'with' => ['roles'],
    ],

    'vendor' => [
        'model' => App\Models\Vendor::class,
        'label' => 'name',
        'with' => [],
    ],

    'package' => [
        'model' => App\Models\Package::class,
        'label' => 'name',
        'with' => [],
    ],
    'test' => [
        'model' => App\Models\Test::class,
        'label' => 'name',
        'with' => [],
    ],
];
