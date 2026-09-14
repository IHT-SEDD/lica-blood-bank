<?php

return [
    'blood-pack' => [
        'view' => 'pages.master.blood-pack.index',
        'model' => App\Models\BloodPack::class,
        'with' => [],
        'label' => 'Kantong Darah',
    ],
    'role' => [
        'view' => 'pages.master.role.index',
        'model' => Spatie\Permission\Models\Role::class,
        'label' => 'Jabatan Akun',
    ],
    'storage' => [
        'view' => 'pages.master.storage.index',
        'model' => App\Models\Storage::class,
        'label' => 'Kulkas Darah',
    ],
    'storage-rack' => [
        'view' => 'pages.master.storage-rack.index',
        'model' => App\Models\StorageRack::class,
        'with' => ['storages'],
        'label' => 'Rak Kulkas Darah',
    ],
    'user' => [
        'view' => 'pages.master.user.index',
        'model' => App\Models\User::class,
        'with' => ['roles'],
        'label' => 'Akun/Pengguna',
    ],
    'vendor' => [
        'view' => 'pages.master.vendor.index',
        'model' => App\Models\Vendor::class,
        'label' => 'PMI',
    ],
    'patient' => [
        'view' => 'pages.master.patient.index',
        'model' => App\Models\Patient::class,
        'label' => 'Pasien',
    ],
    'insurance' => [
        'view' => 'pages.master.insurance.index',
        'model' => App\Models\Insurance::class,
        'label' => 'Penjamin/Asuransi',
    ],
    'doctor' => [
        'view' => 'pages.master.doctor.index',
        'model' => App\Models\Doctor::class,
        'label' => 'Dokter',
    ],
    'room' => [
        'view' => 'pages.master.room.index',
        'model' => App\Models\Room::class,
        'label' => 'Ruangan',
    ],
    'test' => [
        'view' => 'pages.master.test.index',
        'model' => App\Models\Test::class,
        'label' => 'Pemeriksaan',
    ],
    'package' => [
        'view' => 'pages.master.package.index',
        'model' => App\Models\Package::class,
        'with' => ['package_tests.test'],
        'label' => 'Paket Pemeriksaan',
    ],
    'transfusion-reaction' => [
        'view' => 'pages.master.transfusion-reaction.index',
        'model' => App\Models\TransfusionReaction::class,
        'label' => 'Reaksi Transfusi',
    ],
];
