<?php

return [
    'expedition-book' => [
        'view' => 'pages.report.expedition-book.index',
        'label' => 'Buku Expedisi',
        'deskripsi' => 'Buku ekspedisi',
    ],
    'blood-expire' => [
        'view' => 'pages.report.blood-expire.index',
        'label' => 'Darah Kadaluarsa',
        'deskripsi' => 'Laporan terkait stok darah kadaluarsa (expired) yang belum diproses atau belum tercatat keluar pada sistem.',
    ],
    'blood-destroy' => [
        'view' => 'pages.report.blood-destroy.index',
        'label' => 'Darah Dimusnahkan',
        'deskripsi' => 'Laporan terkait stok darah yang telah dimusnahkan pada sistem beserta dengan alasan pemusnahannya.',
    ],
    'incompatible' => [
        'view' => 'pages.report.incompatible.index',
        'label' => 'Hasil Incompatible',
        'deskripsi' => 'Laporan terkait hasil crossmatch pasien yang incompatible.',
    ],
    'blood-usage' => [
        'view' => 'pages.report.blood-usage.index',
        'label' => 'Penggunaan Darah',
        'deskripsi' => 'Laporan terkait penggunaan darah pada pasien baik yang tercatat dikeluarkan maupun tidak dikeluarkan pada sistem.',
    ],
    'blood-request' => [
        'view' => 'pages.report.blood-request.index',
        'label' => 'Permintaan Dropping Darah',
        'deskripsi' => 'Laporan terkait permintaan dropping darah ke PMI untuk stok darah pada sistem.',
    ],
    'blood-stock' => [
        'view' => 'pages.report.blood-stock.index',
        'label' => 'Stok Darah',
        'deskripsi' => 'Laporan terkait stok darah per bulan pada sistem.',
    ],
];
