<?php

namespace App\Services\Inventory;

use App\Enums\BloodPackStatus;
use App\Enums\IncomingBloodStatus;
use App\Enums\OrderBloodStatus;
use App\Enums\OrderLogActivityStatus;
use App\Models\BloodPack;
use App\Models\IncomingBlood;
use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use App\Models\OrderLogActivity;
use App\Models\Vendor;
use Faker\Core\Blood;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StockInService
{
  // ---------- Fungsi untuk menampilkan data ke tabel :begin ----------
  public function stockInTable(Request $request)
  {
    // ---------- Ambil data ----------
    $query = IncomingBlood::withTrashed()
      ->select([
        'id',
        'public_id',
        'order_blood_id',
        'po_number',
        'batch_number',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
      ])
      ->with([
        'users',
        'orderBloods:id,public_id,vendor_id,po_number',
        'orderBloods.vendors:id,public_id,name',
        'bloodPacks' => function ($q) {
          $q->withTrashed()
            ->select('public_id', 'incoming_blood_id');
        }
      ])
      ->withCount([
        'bloodPacks as total_blood_data' => function ($q) {
          $q->withTrashed();
        }
      ]);

    // ---------- Filter tanggal ----------
    $this->applyDateFilter($query, $request);

    // ---------- Filter vendor ----------
    if ($request->filled('vendor')) {
      $query->whereHas('orderBloods.vendors', function ($q) use ($request) {
        $q->where('public_id', $request->vendor);
      });
    }

    // ---------- Filter status ----------
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    // ---------- Search ----------
    if ($request->filled('search')) {
      $search = $request->search;

      $query->where(function ($q) use ($search) {
        $q->where('po_number', 'like', "%{$search}%")
          ->orWhereHas('orderBloods.vendors', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
          });
      });
    }

    // ---------- Sorting ----------
    if ($request->filled('sort_by')) {
      $query->orderBy(
        $request->sort_by,
        $request->sort_dir ?? 'asc'
      );
    } else {
      $query->latest();
    }

    // ---------- Return ----------
    return $query->paginate($request->get('per_page', 10));
  }
  // ---------- Fungsi untuk menampilkan data ke tabel :end ----------

  // ---------- Fungsi untuk mengambil data berdasarkan id :begin ----------
  public function getData(string $id)
  {
    // ---------- Ambil data ----------
    $dataStockIn = IncomingBlood::withTrashed()->where('public_id', $id)->first();

    if (!$dataStockIn) {
      return response()->json(['message' => 'Data not found'], 404);
    }

    // ---------- Return ----------
    return $dataStockIn;
  }
  // ---------- Fungsi untuk mengambil data berdasarkan id :end ----------

  // ---------- Fungsi untuk menambahkan data order baru :begin ----------
  public function insertIncomingStockByManual(Request $request)
  {
    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      $bloodDataItems = $request->input('blood_data', []);

      $orderBlood = OrderBlood::where('po_number', $request->po_number)->first();

      $incomingBlood = IncomingBlood::firstOrCreate(
        [
          // Kondisi pencarian — kombinasi unik
          'po_number' => $request->po_number,
          'batch_number' => $request->batch_number,
        ],
        [
          // Nilai yang diisi hanya saat CREATE baru
          'order_blood_id' => $orderBlood?->id,
          'status' => IncomingBloodStatus::STOCK_REGISTERED,
          'received_by_user_id' => null,
          'received_at' => null,
        ]
      );

      $incomingBagNumbers = collect($bloodDataItems)->pluck('bag_number');

      $duplicateBagNumbers = BloodPack::whereIn('bag_number', $incomingBagNumbers)
        ->pluck('bag_number')
        ->toArray();

      if (!empty($duplicateBagNumbers)) {
        DB::rollBack();
        return response()->json([
          'message' => 'Duplicate bag number detected!',
          'duplicates' => $duplicateBagNumbers,
        ], 422);
      }

      $bloodPacks = [];
      foreach ($bloodDataItems as $item) {
        $bagNumberLica = $this->generateBagNumber(
          $item['blood_group'],
          $item['blood_component']
        );

        $bloodPacks[] = [
          'incoming_blood_id' => $incomingBlood->id,
          'bag_number' => $item['bag_number'],
          'bag_number_lica' => $bagNumberLica,
          'blood_group' => $item['blood_group'],
          'rhesus' => $item['rhesus'],
          'blood_component' => $item['blood_component'],
          'blood_volume' => $item['blood_volume'],
          'aftap_date' => Carbon::createFromFormat('d-m-Y', $item['aftap_date'])->toDateString(),
          'expiry_date' => Carbon::createFromFormat('d-m-Y', $item['expiry_date'])->toDateString(),
          'process_date' => Carbon::createFromFormat('d-m-Y', $item['process_date'])->toDateString(),
          'is_hiv' => (bool) ($item['is_hiv'] ?? false),
          'is_hbsag' => (bool) ($item['is_hbsag'] ?? false),
          'is_hcv' => (bool) ($item['is_hcv'] ?? false),
          'is_syphilis' => (bool) ($item['is_syphilis'] ?? false),
          // blood_status diambil dari item jika ada, default REGISTERED
          'blood_status'      => BloodPackStatus::REGISTERED,
          // public_id tidak di-insert massal — perlu di-generate manual
          // karena BloodPack::insert() bypass model booted()
          'public_id' => (string) \Illuminate\Support\Str::uuid(),
          'created_at' => now(),
          'updated_at' => now(),
        ];
      }

      BloodPack::insert($bloodPacks);

      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger(
        'info',
        'New incoming blood data inserted succesfully!',
        [
          'po_number' => $request->po_number,
          'batch_number' => $request->batch_number,
          'is_new' => $incomingBlood->wasRecentlyCreated,
          'payload' => $incomingBlood,
          'inserted_by' => Auth::user()->id,
        ],
        200,
        'newincomingbloodadd'
      );

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => 'New incoming blood data inserted succesfully!',
        'data' => $incomingBlood
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger(
        'error',
        'New incoming blood data failed to insert!',
        [
          'payload' => $request->all(),
          'error' => $e->getMessage(),
          'inserted_by' => Auth::user()->id,
        ],
        500,
        'newincomingbloodadd'
      );

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => 'New incoming blood data failed to insert!',
        'error' => $e->getMessage(),
      ], 500);
    }
  }
  // ---------- Fungsi untuk menambahkan data order baru :end ----------

  // ---------- Fungsi untuk insert incoming stock via import file excel :begin ----------
  public function insertIncomingStockByExcel(Request $request)
  {
    DB::beginTransaction();

    try {
      // Baca file excel — kolom diambil dari baris pertama sebagai header
      $rows = Excel::toCollection(null, $request->file('excel_file'))
        ->first()
        ->skip(1)
        ->values();

      if ($rows->isEmpty()) {
        return response()->json([
          'message' => 'File excel tidak memiliki data!',
        ], 422);
      }

      // ---------- Mapping kolom excel ke field database :begin ----------
      // Urutan kolom yang diharapkan di file excel:
      // A: bag_number | B: blood_group | C: rhesus | D: blood_component
      // E: volume     | F: aftap_date  | G: expiry_date | H: process_date
      // I: quantity   | J: is_hiv      | K: is_hcv | L: is_hbsag | M: is_syphilis
      // ---------- Mapping kolom excel ke field database :end ----------

      $createdIncomingBloods = [];

      foreach ($rows as $row) {
        // Lewati baris kosong
        if ($row->filter()->isEmpty()) continue;

        $item = [
          'bag_number' => $row[0],
          'blood_group' => $row[1],
          'blood_rhesus' => $row[2],
          'blood_component' => $row[3],
          'volume' => $row[4],
          'aftap_date' => $row[5],
          'expiry_date' => $row[6],
          'process_date' => $row[7],
          'quantity' => (int) $row[8],
          'is_hiv' => (bool) $row[9],
          'is_hcv' => (bool) $row[10],
          'is_hbsag' => (bool) $row[11],
          'is_syphilis' => (bool) $row[12],
        ];

        // Cari order_blood yang sesuai
        $orderBlood = OrderBlood::whereHas('orderHistory', function ($q) use ($request) {
          $q->where('po_number', $request->po_number);
        })
          ->where('blood_group', $item['blood_group'])
          ->where('rhesus', $item['blood_rhesus'])
          ->where('blood_component', $item['blood_component'])
          ->first();

        $incomingBlood = IncomingBlood::create([
          'order_blood_id'      => $orderBlood?->id,
          'po_number'           => $request->po_number,
          'batch_number'        => $request->batch_number,
          'status'              => \App\Enums\IncomingBloodStatus::STOCK_REGISTERED->value,
          'received_by_user_id' => Auth::id(),
          'received_at'         => now(),
        ]);

        // ---------- Parse tanggal dari excel — tangani format angka serial Excel :begin ----------
        // Excel menyimpan tanggal sebagai angka serial (misal: 46000)
        // Jika sudah string (d-m-Y atau Y-m-d), parse langsung
        $aftapDate   = $this->parseExcelDate($item['aftap_date']);
        $expiryDate  = $this->parseExcelDate($item['expiry_date']);
        $processDate = $this->parseExcelDate($item['process_date']);
        // ---------- Parse tanggal dari excel :end ----------

        $bloodPacks = [];
        for ($i = 0; $i < $item['quantity']; $i++) {
          $bloodPacks[] = [
            'incoming_blood_id' => $incomingBlood->id,
            'bag_number' => $item['bag_number'],
            'blood_group' => $item['blood_group'],
            'rhesus' => $item['blood_rhesus'],
            'blood_component' => $item['blood_component'],
            'blood_volume' => $item['volume'],
            'aftap_date' => $aftapDate,
            'expiry_date' => $expiryDate,
            'process_date' => $processDate,
            'is_hiv' => $item['is_hiv'],
            'is_hbsag' => $item['is_hbsag'],
            'is_hcv' => $item['is_hcv'],
            'is_syphilis' => $item['is_syphilis'],
            'blood_status' => 'available',
          ];
        }

        BloodPack::insert($bloodPacks);
        $createdIncomingBloods[] = $incomingBlood;
      }

      DB::commit();

      globalLogger(
        'info',
        'New incoming blood data via Excel inserted successfully!',
        ['po_number' => $request->po_number, 'total_items' => count($createdIncomingBloods)],
        200,
        'newincomingbloodadd'
      );

      return response()->json([
        'message' => 'New incoming blood data inserted successfully!',
        'data' => $createdIncomingBloods,
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      globalLogger(
        'error',
        'New incoming blood data via Excel failed to insert!',
        ['payload' => $request->all(), 'error' => $e->getMessage()],
        500,
        'newincomingbloodadd'
      );

      return response()->json([
        'message' => 'New incoming blood data failed to insert!',
      ], 500);
    }
  }
  // ---------- Fungsi untuk insert incoming stock via import file excel :end ----------

  // ---------- Fungsi untuk delete data :begin ----------
  public function deleteDataStockIn(string $id)
  {
    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      $incomingBlood = IncomingBlood::where('public_id', $id)->first();

      if (!$incomingBlood) {
        return response()->json([
          'message' => 'Data not found'
        ], 404);
      }

      BloodPack::where('incoming_blood_id', $incomingBlood->id)->delete();
      $incomingBlood->delete();

      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger(
        'info',
        "Data stock in deleted successfully!",
        [
          'id' => $incomingBlood->id,
          'deleted_by' => Auth::user()->id,
        ],
        200,
        'newincomingblooddelete'
      );

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => "Data stock in deleted successfully!",
        'data' => $incomingBlood
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger(
        'error',
        "Data stock in failed to delete!",
        [
          'data' => $id,
          'deleted_by' => Auth::user()->id,
          'error' => $e->getMessage(),
        ],
        500,
        'newincomingblooddelete'
      );

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => "Data stock in failed to delete!",
      ], 500);
    }
  }
  // ---------- Fungsi untuk delete data :end ----------

  // ---------- Fungsi untuk restore data :begin ----------
  public function restoreDataStockIn(string $id)
  {
    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      $incomingBlood = IncomingBlood::onlyTrashed()->where('public_id', $id)->first();

      if (!$incomingBlood) {
        return response()->json([
          'message' => 'Data not found'
        ], 404);
      }

      BloodPack::onlyTrashed()->where('incoming_blood_id', $incomingBlood->id)->restore();
      $incomingBlood->restore();

      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger(
        'info',
        "Data stock in restored successfully!",
        [
          'id' => $incomingBlood->id,
          'restored_by' => Auth::user()->id,
        ],
        200,
        'newincomingbloodrestore'
      );

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => "Data stock in restored successfully!",
        'data' => $incomingBlood
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger(
        'error',
        "Data stock in failed to restore!",
        [
          'data' => $id,
          'error' => $e->getMessage(),
          'restored_by' => Auth::user()->id,
        ],
        500,
        'newincomingbloodrestore'
      );

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => "Data stock in failed to restore!",
      ], 500);
    }
  }
  // ---------- Fungsi untuk delete data :begin ----------

  // ---------- Fungsi untuk membuat po number :begin ----------
  public function generateBagNumber(string $bloodGroup, string $bloodComponent): string
  {
    return retry(5, function () use ($bloodGroup, $bloodComponent) {

      return DB::transaction(function () use ($bloodGroup, $bloodComponent) {

        $year = now()->format('Y');
        $random = rand(100, 999);

        $prefix = "BP{$bloodGroup}{$random}{$bloodComponent}{$year}";

        $last = BloodPack::where('bag_number_lica', 'like', "{$prefix}%")
          ->lockForUpdate()
          ->orderByDesc('bag_number_lica')
          ->first();

        $nextNumber = $last
          ? ((int) substr($last->bag_number_lica, -4) + 1)
          : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
      });
    }, 100);
  }
  // ---------- Fungsi untuk membuat po number :end ----------

  // ---------- Helper: parse tanggal dari nilai excel (string atau angka serial) :begin ----------
  private function parseExcelDate(mixed $value): string
  {
    // Jika berupa angka, konversi dari serial date Excel ke Carbon
    if (is_numeric($value)) {
      return Carbon::instance(
        Date::excelToDateTimeObject($value)
      )->toDateString();
    }

    // Coba parse format d-m-Y (dari input manual), fallback ke format umum
    try {
      return Carbon::createFromFormat('d-m-Y', $value)->toDateString();
    } catch (\Exception) {
      return Carbon::parse($value)->toDateString();
    }
  }
  // ---------- Helper: parse tanggal dari nilai excel :end ----------

  // ---------- Helper: untuk menerima dan menerapkan filter tanggal pada data :begin ----------
  protected function applyDateFilter(Builder $query, Request $request)
  {
    // ---------- Terima data start_date & end_date dari frontend ----------
    $start = $request->start_date;
    $end = $request->end_date;

    if ($start && $end) {
      try {
        // ---------- Format data tanggal menjadi d-m-Y H:i ----------
        $startDate = Carbon::createFromFormat('d-m-Y', $start)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $end)->endOfDay();

        // ---------- Cek apakah kolom ada atau tidak ----------
        $table = $query->getModel()->getTable();

        // ---------- Jalankan filter data ----------
        if (Schema::hasColumn($table, 'created_at')) {
          $query->whereBetween('created_at', [$startDate, $endDate]);
        }
      } catch (\Exception $e) {
        logger()->error('Date filter error: ' . $e->getMessage());
      }
    }
  }
  // ---------- Helper: untuk menerima dan menerapkan filter tanggal pada data :end ----------
}
