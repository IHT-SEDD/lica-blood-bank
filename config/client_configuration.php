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
  // Blood transfusion
  'blood_transfusion' => [
   'recommendation_blood_bag' => false,
   'reaction_transfusion_via_select' => false,
   'archive_transfusion' => false,
  ],
  // Sidebar navigations
  'sidenav' => [
   'archive_page' => false,
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
  // RS PKU MUHAMMADIYAH JOGJA
  'rs_pku_muhammadiyah_jogja' => [
   'blood_transfusion' => [
    'recommendation_blood_bag' => true,
    'reaction_transfusion_via_select' => false,
    'archive_transfusion' => false,
   ],
   'sidenav' => [
    'archive_page' => false,
   ],
  ],

  // RSUD INDRAMAYU
  'rsud_indramayu' => [
   'blood_transfusion' => [
    'recommendation_blood_bag' => false,
    'reaction_transfusion_via_select' => false,
    'archive_transfusion' => false,
   ],
   'sidenav' => [
    'archive_page' => false,
   ],
  ],
 ],

];
