<?php

// --- Mapping kode jenis test atau keyword atau label componentnya untuk mendapatkan value component
return [
    'PRC' => [
        'label' => 'Packed Red Cells',
        'keywords' => ['PRC', 'Packed Red Cells', 'Thalasemia', 'Thalassemia', 'Labu Darah'],
        'general_codes' => ['543', '2461', '2462', '2463', '2464', '2784'],
    ],
    'TC' => [
        'label' => 'Trombocyte Concentrate',
        'keywords' => ['TC', 'Trombocyte', 'Trombosit', 'Platelet'],
        'general_codes' => ['2472'],
    ],
    'FFP' => [
        'label' => 'Fresh Frozen Plasma',
        'keywords' => ['FFP', 'Fresh Frozen Plasma', 'Frozen Plasma'],
        'general_codes' => ['2467'],
    ],
    'LP' => [
        'label' => 'Liquid Plasma',
        'keywords' => ['LP', 'Liquid Plasma', 'Plasma'],
        'general_codes' => ['2466'],
    ],
    'CRYO' => [
        'label' => 'Cryoprecipitate',
        'keywords' => ['CRYO', 'Cryoprecipitate', 'Kriopresipitat'],
        'general_codes' => [],
    ],
    'WRC' => [
        'label' => 'Washed Red Cells',
        'keywords' => ['WRC', 'Washed Red Cells'],
        'general_codes' => ['2469'],
    ],
    'WB' => [
        'label' => 'Whole Blood',
        'keywords' => ['WB', 'Whole Blood', 'Darah Lengkap'],
        'general_codes' => [],
    ],
];
