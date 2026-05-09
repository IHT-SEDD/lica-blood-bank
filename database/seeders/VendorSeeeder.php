<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendorData = [
            [
                'name' => 'PMI Kab. Indramayu',
                'address' => 'Jl. Yos Sudarso No.35, Margadadi, Kec. Indramayu, Kabupaten Indramayu, Jawa Barat 45211',
                'phone_number' => null,
                'telephone_number' => null,
                'pic_name' => null,
                'is_active' => true,
            ],
            [
                'name' => 'PMI Kota Cirebon',
                'address' => 'Jl. DR. Sudarsono No.18, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134',
                'phone_number' => null,
                'telephone_number' => null,
                'pic_name' => null,
                'is_active' => true,
            ],
            [
                'name' => 'PMI Kota Bandung',
                'address' => 'Jl. Aceh No.79, Cihapit, Kec. Bandung Wetan, Kota Bandung, Jawa Barat 40114',
                'phone_number' => null,
                'telephone_number' => null,
                'pic_name' => null,
                'is_active' => true,
            ],
            [
                'name' => 'PMI Kab. Bandung',
                'address' => 'Jl. Terusan Al Fathu No.KM. 17, Cingcin, Kec. Soreang, Kabupaten Bandung, Jawa Barat 40911',
                'phone_number' => null,
                'telephone_number' => null,
                'pic_name' => null,
                'is_active' => true,
            ],
        ];

        foreach ($vendorData as $vendor) {

            Vendor::firstOrCreate(
                [
                    'name' => $vendor['name'],
                ],
                [
                    'address' => $vendor['address'],
                    'phone_number' => $vendor['phone_number'],
                    'telephone_number' => $vendor['telephone_number'],
                    'pic_name' => $vendor['pic_name'],
                    'is_active' => $vendor['is_active'],
                ]
            );
        }
    }
}
