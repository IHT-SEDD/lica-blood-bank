<?php

return [
 /*
 |--------------------------------------------------------------------------
 | Default Configuration
 |--------------------------------------------------------------------------
 |
 | Konfigurasi default per module, dipakai jika client tidak override
 | atau client_name tidak dikenali dalam mapping di bawah.
 |
 */
 'default' => [
  'blood_transfusion' => [
   'recommendation_blood_bag' => false,
   'reaction_transfusion_via_select' => false,
  ],
 ],

 /*
 |--------------------------------------------------------------------------
 | Per-Client Overrides
 |--------------------------------------------------------------------------
 |
 | Key menggunakan slug client (lowercase, spasi/simbol jadi underscore),
 | sama seperti slug yang dipakai untuk resolve view di
 | BloodTransfusionPrintService. Hanya perlu isi module/key yang BERBEDA
 | dari default; sisanya otomatis fallback ke 'default' di atas.
 |
 */
 'clients' => [
  'rs_pku_muhammadiyah_jogja' => [
   'blood_transfusion' => [
    'recommendation_blood_bag' => true,
    'reaction_transfusion_via_select' => false,
   ],
  ],
  'rsud_indramayu' => [
   'blood_transfusion' => [
    'recommendation_blood_bag' => false,
    'reaction_transfusion_via_select' => false,
   ],
  ],
 ],

];
