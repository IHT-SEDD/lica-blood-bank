<?php

namespace Database\Seeders;

use App\Models\LogIntegration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LogIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing log integrations to avoid duplicate keys or confusion
        LogIntegration::truncate();

        $now = Carbon::now();

        // --- Mock data for receive data (new_request) ---
        $receiveLogs = [
            [
                'order_number' => 'ORD-20260609-0001',
                'endpoint' => 'http://lica-blood-bank.test/api/v1/blood-transfusion',
                'message' => 'Transaksi permintaan darah sukses ditambahkan',
                'status' => 'success',
                'type' => 'new_request',
                'payload' => [
                    'demografi' => [
                        'no_rkm_medis' => '123456',
                        'nama_pasien' => 'Ahmad Faisal',
                        'jk' => 'L',
                        'tgl_lahir' => '1990-05-15',
                        'alamat' => 'Jl. Merdeka No. 10',
                    ],
                    'transaksi' => [
                        'no_order' => 'ORD-20260609-0001',
                        'tgl_permintaan' => '2026-06-09',
                        'jam_permintaan' => '10:00:00',
                        'jenis' => 'Rawat Inap',
                        'kelas' => '1',
                        'ruangan' => 'Bougenville',
                        'kode_ruangan' => 'R001',
                        'pembayaran' => 'BPJS Kesehatan',
                        'kode_pembayaran' => 'BPJS',
                        'dokter' => 'Dr. Budi Utomo',
                        'kode_dokter' => 'D001',
                    ],
                    'tes' => [
                        [
                            'kode_jenis_tes' => '2454',
                            'nama_tes' => 'Crossmatch',
                        ],
                        [
                            'kode_jenis_tes' => '1111',
                            'nama_tes' => 'Packed Red Cells',
                        ],
                    ]
                ],
                'created_at' => $now,
            ],
            [
                'order_number' => 'ORD-20260609-0002',
                'endpoint' => 'http://lica-blood-bank.test/api/v1/blood-transfusion',
                'message' => 'Invalid transaction type: Rawat Jalan',
                'status' => 'failed',
                'type' => 'new_request',
                'payload' => [
                    'demografi' => [
                        'no_rkm_medis' => '654321',
                        'nama_pasien' => 'Siti Aminah',
                        'jk' => 'P',
                        'tgl_lahir' => '1985-08-20',
                    ],
                    'transaksi' => [
                        'no_order' => 'ORD-20260609-0002',
                        'jenis' => 'Rawat Jalan Palsu',
                    ],
                    'tes' => []
                ],
                'created_at' => $now->copy()->subMinutes(15),
            ],
            [
                'order_number' => 'ORD-20260608-0005',
                'endpoint' => 'http://lica-blood-bank.test/api/v1/blood-transfusion',
                'message' => 'Transaksi permintaan darah sukses ditambahkan',
                'status' => 'success',
                'type' => 'new_request',
                'payload' => [
                    'demografi' => [
                        'no_rkm_medis' => '112233',
                        'nama_pasien' => 'Eko Prasetyo',
                        'jk' => 'L',
                        'tgl_lahir' => '1995-12-01',
                    ],
                    'transaksi' => [
                        'no_order' => 'ORD-20260608-0005',
                        'tgl_permintaan' => '2026-06-08',
                        'jam_permintaan' => '14:30:00',
                        'jenis' => 'IGD',
                        'kelas' => '3',
                        'ruangan' => 'IGD Room',
                        'kode_ruangan' => 'R005',
                        'pembayaran' => 'Umum',
                        'kode_pembayaran' => 'UMUM',
                        'dokter' => 'Dr. Sarah Lestari',
                        'kode_dokter' => 'D005',
                    ],
                    'tes' => [
                        [
                            'kode_jenis_tes' => '1111',
                            'nama_tes' => 'Packed Red Cells',
                        ],
                    ]
                ],
                'created_at' => $now->copy()->subDay(),
            ],
            [
                'order_number' => 'ORD-20260607-0010',
                'endpoint' => 'http://lica-blood-bank.test/api/v1/blood-transfusion',
                'message' => 'Transaksi permintaan darah sukses ditambahkan',
                'status' => 'success',
                'type' => 'new_request',
                'payload' => [
                    'demografi' => [
                        'no_rkm_medis' => '445566',
                        'nama_pasien' => 'Rina Wijaya',
                        'jk' => 'P',
                        'tgl_lahir' => '1992-04-10',
                    ],
                    'transaksi' => [
                        'no_order' => 'ORD-20260607-0010',
                        'tgl_permintaan' => '2026-06-07',
                        'jam_permintaan' => '08:15:00',
                        'jenis' => 'Rawat Inap',
                        'kelas' => '2',
                        'ruangan' => 'Melati',
                        'kode_ruangan' => 'R002',
                        'pembayaran' => 'Asuransi Swasta',
                        'kode_pembayaran' => 'ASR_SWAS',
                        'dokter' => 'Dr. Budi Utomo',
                        'kode_dokter' => 'D001',
                    ],
                    'tes' => [
                        [
                            'kode_jenis_tes' => '2222',
                            'nama_tes' => 'Thrombocyte Concentrate',
                        ],
                    ]
                ],
                'created_at' => $now->copy()->subDays(2),
            ],
        ];

        // --- Mock data for send result (send_result) ---
        $sendLogs = [
            [
                'order_number' => 'ORD-20260609-0001',
                'endpoint' => 'http://simrs-api.test/api/v1/integration/send-result',
                'message' => 'Hasil permintaan darah sukses terkirim ke SIMRS',
                'status' => 'success',
                'type' => 'send_result',
                'payload' => [
                    'order_number' => 'ORD-20260609-0001',
                    'simrs_response' => [
                        'status' => 'success',
                        'message' => 'Data received successfully',
                    ],
                    'payload_sent' => [
                        'no_ref' => 'ORD-20260609-0001',
                        'tgl_kirim' => '2026-06-09 11:30:00',
                        'hasil' => [
                            [
                                'test_id' => 1,
                                'test_name' => 'Crossmatch Mayor',
                                'kode_jenis_tes' => '2454',
                                'result' => 'Compatible',
                                'result_status' => 'normal',
                                'notes' => 'Cocok, aman ditransfusikan',
                            ],
                            [
                                'test_id' => 2,
                                'test_name' => 'Crossmatch Minor',
                                'kode_jenis_tes' => '2454',
                                'result' => 'Compatible',
                                'result_status' => 'normal',
                                'notes' => 'Cocok, aman ditransfusikan',
                            ],
                        ],
                    ],
                ],
                'created_at' => $now->copy()->addMinutes(30),
            ],
            [
                'order_number' => 'ORD-20260609-0003',
                'endpoint' => 'http://simrs-api.test/api/v1/integration/send-result',
                'message' => 'Failed to send result to SIMRS. HTTP 500 Internal Server Error',
                'status' => 'failed',
                'type' => 'send_result',
                'payload' => [
                    'order_number' => 'ORD-20260609-0003',
                ],
                'created_at' => $now->copy()->addMinutes(45),
            ],
            [
                'order_number' => 'ORD-20260608-0005',
                'endpoint' => 'http://simrs-api.test/api/v1/integration/send-result',
                'message' => 'Hasil permintaan darah sukses terkirim ke SIMRS',
                'status' => 'success',
                'type' => 'send_result',
                'payload' => [
                    'order_number' => 'ORD-20260608-0005',
                    'simrs_response' => [
                        'status' => 'success',
                        'message' => 'Data received successfully',
                    ],
                    'payload_sent' => [
                        'no_ref' => 'ORD-20260608-0005',
                        'tgl_kirim' => '2026-06-08 16:00:00',
                        'hasil' => [
                            [
                                'test_id' => 1,
                                'test_name' => 'Crossmatch Mayor',
                                'kode_jenis_tes' => '2454',
                                'result' => 'Compatible',
                                'result_status' => 'normal',
                            ],
                        ],
                    ],
                ],
                'created_at' => $now->copy()->subDay()->addHours(2),
            ],
        ];

        // Combine logs
        $allLogs = array_merge($receiveLogs, $sendLogs);

        foreach ($allLogs as $log) {
            LogIntegration::create([
                'public_id' => (string) Str::uuid(),
                'order_number' => $log['order_number'],
                'endpoint' => $log['endpoint'],
                'payload' => $log['payload'],
                'message' => $log['message'],
                'status' => $log['status'],
                'type' => $log['type'],
                'is_active' => true,
                'created_at' => $log['created_at'],
                'updated_at' => $log['created_at'],
            ]);
        }
    }
}
