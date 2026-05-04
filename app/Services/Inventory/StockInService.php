<?php

namespace App\Services\Inventory;

use App\Enums\BloodPackStatus;
use App\Enums\IncomingBloodStatus;
use App\Enums\OrderBloodStatus;
use App\Enums\OrderLogActivityStatus;
use App\Models\BloodPack;
use App\Models\IncomingBlood;
use App\Models\IncomingBloodLogActivity;
use App\Enums\IncomingBloodLogActivityStatus;
use App\Models\BloodStock;
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
use Maatwebsite\Excel\Concerns\ToArray;

class StockInService
{
  // ---------- Fungsi untuk menampilkan data ke tabel :begin ----------
  public function stockInTable(Request $request)
  {
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
        'bloodStocks' => function ($q) {
          $q->withTrashed()->select('public_id', 'incoming_blood_id');
        }
      ])
      ->withCount([
        'bloodStocks as total_blood_data' => function ($q) {
          $q->withTrashed();
        }
      ]);

    $this->applyDateFilter($query, $request);

    if ($request->filled('vendor')) {
      $query->whereHas('orderBloods.vendors', function ($q) use ($request) {
        $q->where('public_id', $request->vendor);
      });
    }

    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('po_number', 'like', "%{$search}%")
          ->orWhereHas('orderBloods.vendors', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
          });
      });
    }

    if ($request->filled('sort_by')) {
      $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
    } else {
      $query->latest();
    }

    return $query->paginate($request->get('per_page', 10));
  }
  // ---------- Fungsi untuk menampilkan data ke tabel :end ----------

  // ---------- Fungsi untuk mengambil data berdasarkan id :begin ----------
  public function getData(string $id)
  {
    $dataStockIn = IncomingBlood::withTrashed()->where('public_id', $id)->first();

    if (!$dataStockIn) {
      return response()->json(['message' => 'Data not found'], 404);
    }

    return $dataStockIn;
  }
  // ---------- Fungsi untuk mengambil data berdasarkan id :end ----------

  // ---------- Fungsi untuk menambahkan data order baru :begin ----------
  public function insertIncomingStockByManual(Request $request)
  {
    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      $user = Auth::user();
      $bloodDataItems = $request->input('blood_data', []);

      $orderBlood = OrderBlood::where('po_number', $request->po_number)->first();

      $incomingBlood = IncomingBlood::firstOrCreate(
        [
          'po_number' => $request->po_number,
          'batch_number' => $request->batch_number,
        ],
        [
          'order_blood_id' => $orderBlood?->id,
          'status' => IncomingBloodStatus::STOCK_REGISTERED,
          'received_by_user_id' => null,
          'received_at' => null,
        ]
      );

      $incomingBagNumbers = collect($bloodDataItems)->pluck('bag_number');

      $duplicateBagNumbers = BloodStock::whereIn('bag_number', $incomingBagNumbers)
        ->pluck('bag_number')
        ->toArray();

      if (!empty($duplicateBagNumbers)) {
        DB::rollBack();
        return response()->json([
          'message' => 'Duplicate bag number detected!',
          'duplicates' => $duplicateBagNumbers,
        ], 422);
      }

      $bloodStocks = [];
      foreach ($bloodDataItems as $item) {
        $bloodPack = BloodPack::where('public_id', $item['blood_pack_id'])->firstOrFail();

        $bagNumberLica = $this->generateBagNumber(
          $bloodPack->blood_group->value,
          $bloodPack->blood_component->value
        );


        $bloodStocks[] = [
          'incoming_blood_id' => $incomingBlood->id,
          'bag_number' => $item['bag_number'],
          'bag_number_lica' => $bagNumberLica,
          'blood_pack_id' => $bloodPack->id,
          'blood_volume' => $item['blood_volume'],
          'aftap_date' => Carbon::createFromFormat('d-m-Y', $item['aftap_date'])->toDateString(),
          'expiry_date' => Carbon::createFromFormat('d-m-Y', $item['expiry_date'])->toDateString(),
          'process_date' => Carbon::createFromFormat('d-m-Y', $item['process_date'])->toDateString(),
          'is_hiv' => (bool) ($item['is_hiv'] ?? false),
          'is_hbsag' => (bool) ($item['is_hbsag'] ?? false),
          'is_hcv' => (bool) ($item['is_hcv'] ?? false),
          'is_syphilis' => (bool) ($item['is_syphilis'] ?? false),
          'blood_status' => BloodPackStatus::REGISTERED,
          'public_id' => (string) \Illuminate\Support\Str::uuid(),
          'created_at' => now(),
          'updated_at' => now(),
        ];
      }

      BloodStock::insert($bloodStocks);

      // ---------- Insert Incoming Blood Log Activity ----------
      IncomingBloodLogActivity::create([
        'incoming_blood_public_id' => $incomingBlood->public_id,
        'po_number' => $request->po_number,
        'batch_number' => $request->batch_number,
        'incoming_data' => $incomingBlood->toArray(),
        'blood_stock_data' => $bloodStocks,
        'status' => IncomingBloodLogActivityStatus::INCOMING_CREATED_BY_MANUAL,
        'created_by_user_name' => $user->name,
        'description' => generateIncomingLogDescription(
          IncomingBloodLogActivityStatus::INCOMING_CREATED_BY_MANUAL,
          $request->po_number,
          $user->id
        ),
      ]);

      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger('info', 'New incoming blood data inserted succesfully!', [
        'po_number' => $request->po_number,
        'batch_number' => $request->batch_number,
        'is_new' => $incomingBlood->wasRecentlyCreated,
        'payload' => $incomingBlood,
        'inserted_by' => Auth::user()->id,
      ], 200, 'newincomingbloodadd');

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => 'New incoming blood data inserted succesfully!',
        'data' => $incomingBlood
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger('error', 'New incoming blood data failed to insert!', [
        'payload' => $request->all(),
        'error' => $e->getMessage(),
        'inserted_by' => Auth::user()->id,
      ], 500, 'newincomingbloodadd');

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
      $user = Auth::user();
      // ---------- Baca file excel :begin ----------
      $rows = $this->readExcelFile($request->file('excel_file'));

      if (!$rows || $rows->isEmpty()) {
        return response()->json(['message' => 'Excel file is empty or not readable!'], 422);
      }

      // Lewati baris 1 (header), mulai dari baris ke-2 (index 2, 0-based: index 2)
      $dataRows = $rows->values()->filter(function ($row) {
        // Lewati baris yang semua kolomnya kosong
        return $row->filter(fn($val) => !is_null($val) && $val !== '')->isNotEmpty();
      });

      // ---------- Ambil atau buat IncomingBlood record :begin ----------
      $orderBlood = OrderBlood::where('po_number', $request->po_number)->first();

      $incomingBlood = IncomingBlood::firstOrCreate(
        [
          'po_number' => $request->po_number,
          'batch_number' => $request->batch_number,
        ],
        [
          'order_blood_id' => $orderBlood?->id,
          'status' => IncomingBloodStatus::STOCK_REGISTERED,
          'received_by_user_id' => Auth::id(),
          'received_at' => now(),
        ]
      );
      // ---------- Ambil atau buat IncomingBlood record :end ----------

      $bloodPacks = BloodPack::all()->keyBy(function ($item) {
        return strtoupper($item->blood_group->value) . '|' .
          strtoupper($item->blood_rhesus) . '|' .
          strtoupper($item->blood_component->value);
      });

      // ---------- Mapping & validasi data dari excel :begin ----------
      $bloodStocks = [];
      $incomingBagNumbers = [];

      foreach ($dataRows as $rowIndex => $row) {
        $bagNumber      = trim((string) ($row->get(0) ?? ''));
        $bloodGroup     = trim((string) ($row->get(1) ?? ''));
        $rhesus         = trim((string) ($row->get(2) ?? ''));
        $bloodComponent = trim((string) ($row->get(3) ?? ''));
        $volume         = $row->get(4);
        $aftapRaw       = $row->get(5);
        $expiryRaw      = $row->get(6);
        $processRaw     = $row->get(7);
        $hivRaw         = $row->get(8);
        $hcvRaw         = $row->get(9);
        $hbsagRaw       = $row->get(10);
        $syphilisRaw    = $row->get(11);

        // ---------- Validasi field wajib per baris :begin ----------
        $excelRowNumber = $rowIndex + 7; // Row excel ke berapa (1-based, mulai dari 6)
        $missingFields = [];

        if (empty($bagNumber)) $missingFields[] = 'Bag Number';
        if (empty($bloodGroup)) $missingFields[] = 'Group';
        if (empty($rhesus)) $missingFields[] = 'Rhesus';
        if (empty($bloodComponent)) $missingFields[] = 'Component';
        if (is_null($volume)) $missingFields[] = 'Volume';
        if (is_null($aftapRaw)) $missingFields[] = 'Aftap Date';
        if (is_null($expiryRaw)) $missingFields[] = 'Expiry Date';
        if (is_null($processRaw)) $missingFields[] = 'Process Date';

        if (!empty($missingFields)) {
          DB::rollBack();
          return response()->json([
            'message' => "Row {$excelRowNumber}: is required cannot be empty (" . implode(', ', $missingFields) . ").",
          ], 422);
        }
        // ---------- Validasi field wajib per baris :end ----------

        // ---------- Parse tanggal :begin ----------
        $aftapDate = $this->parseExcelDate($aftapRaw);
        $expiryDate = $this->parseExcelDate($expiryRaw);
        $processDate = $this->parseExcelDate($processRaw);
        // ---------- Parse tanggal :end ----------

        // ---------- Parse nilai serologis (HIV, HCV, HBSAG, Syphilis) :begin ----------
        // Template menggunakan "NR" (Non-Reactive) atau "R" (Reactive)
        // Konversi ke boolean: "R" / "Reactive" = true, selainnya = false
        $isHiv = $this->parseSerologicalValue($hivRaw);
        $isHcv = $this->parseSerologicalValue($hcvRaw);
        $isHbsag = $this->parseSerologicalValue($hbsagRaw);
        $isSyphilis = $this->parseSerologicalValue($syphilisRaw);
        // ---------- Parse nilai serologis :end ----------

        $key = "{$bloodGroup}|{$rhesus}|{$bloodComponent}";
        $bloodPack = $bloodPacks->get($key);

        if (!$bloodPack) {
          DB::rollBack();
          return response()->json([
            'message' => "Row {$excelRowNumber}: Blood pack not found ({$bloodGroup}{$rhesus} {$bloodComponent})"
          ], 422);
        }

        $incomingBagNumbers[] = $bagNumber;

        $bloodStocks[] = [
          'incoming_blood_id' => $incomingBlood->id,
          'bag_number' => $bagNumber,
          'bag_number_lica' => $this->generateBagNumber($bloodGroup, $bloodComponent),
          'blood_pack_id' => $bloodPack->id,
          'blood_volume' => $volume,
          'aftap_date' => $aftapDate,
          'expiry_date' => $expiryDate,
          'process_date' => $processDate,
          'is_hiv' => $isHiv,
          'is_hcv' => $isHcv,
          'is_hbsag' => $isHbsag,
          'is_syphilis' => $isSyphilis,
          'blood_status' => BloodPackStatus::REGISTERED,
          'public_id' => (string) \Illuminate\Support\Str::uuid(),
          'created_at' => now(),
          'updated_at' => now(),
        ];
      }
      // ---------- Mapping & validasi data dari excel :end ----------

      // ---------- Cek duplikasi bag number di database :begin ----------
      $duplicateBagNumbers = BloodStock::whereIn('bag_number', $incomingBagNumbers)
        ->pluck('bag_number')
        ->toArray();

      if (!empty($duplicateBagNumbers)) {
        DB::rollBack();
        return response()->json([
          'message' => 'Duplicate bag number detected!',
          'duplicates' => $duplicateBagNumbers,
        ], 422);
      }
      // ---------- Cek duplikasi bag number di database :end ----------

      // ---------- Cek duplikasi bag number di dalam file excel itu sendiri :begin ----------
      $bagNumberCounts = array_count_values($incomingBagNumbers);
      $internalDuplicates = array_keys(array_filter($bagNumberCounts, fn($count) => $count > 1));

      if (!empty($internalDuplicates)) {
        DB::rollBack();
        return response()->json([
          'message' => 'Duplicates bag number detected in file!',
          'duplicates' => $internalDuplicates,
        ], 422);
      }
      // ---------- Cek duplikasi bag number di dalam file excel itu sendiri :end ----------

      // ---------- Bulk insert semua blood stock :begin ----------
      BloodStock::insert($bloodStocks);
      // ---------- Bulk insert semua blood stock :end ----------

      // ---------- Insert Incoming Blood Log Activity ----------
      IncomingBloodLogActivity::create([
        'incoming_blood_public_id' => $incomingBlood->public_id,
        'po_number' => $request->po_number,
        'batch_number' => $request->batch_number,
        'incoming_data' => $incomingBlood->toArray(),
        'blood_stock_data' => $bloodStocks,
        'status' => IncomingBloodLogActivityStatus::INCOMING_CREATED_BY_EXCEL,
        'created_by_user_name' => $user->name,
        'description' => generateIncomingLogDescription(
          IncomingBloodLogActivityStatus::INCOMING_CREATED_BY_EXCEL,
          $request->po_number,
          $user->id
        ),
      ]);

      DB::commit();

      globalLogger('info', 'New incoming blood data via Excel inserted successfully!', [
        'po_number' => $request->po_number,
        'batch_number' => $request->batch_number,
        'total_items' => count($bloodStocks),
        'inserted_by' => Auth::id(),
      ], 200, 'newincomingbloodadd');

      return response()->json([
        'message' => 'New incoming blood data inserted successfully!',
        'data' => $incomingBlood,
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      globalLogger('error', 'New incoming blood data via Excel failed to insert!', [
        'payload' => $request->except('excel_file'),
        'error' => $e->getMessage(),
        'inserted_by' => Auth::id(),
      ], 500, 'newincomingbloodadd');

      return response()->json([
        'message' => 'New incoming blood data failed to insert!',
        'error' => $e->getMessage(),
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
      $user = Auth::user();
      $incomingBlood = IncomingBlood::where('public_id', $id)->first();

      if (!$incomingBlood) {
        return response()->json(['message' => 'Data incoming blood not found'], 404);
      }

      $bloodStocks = BloodStock::where('incoming_blood_id', $incomingBlood->id)->get();

      if ($bloodStocks->isEmpty()) {
        return response()->json(['message' => 'Data blood pack not found'], 404);
      }

      $incomingData = $incomingBlood->toArray();
      $bloodStockData = $bloodStocks->toArray();

      $bloodStocks->each->delete();
      $incomingBlood->delete();

      // ---------- Insert Incoming Blood Log Activity ----------
      IncomingBloodLogActivity::create([
        'incoming_blood_public_id' => $incomingBlood->public_id,
        'po_number' => $incomingBlood->po_number,
        'batch_number' => $incomingBlood->batch_number,
        'incoming_data' => $incomingData,
        'blood_stock_data' => $bloodStockData,
        'status' => IncomingBloodLogActivityStatus::INCOMING_DELETED,
        'created_by_user_name' => $user->name,
        'description' => generateIncomingLogDescription(
          IncomingBloodLogActivityStatus::INCOMING_DELETED,
          $incomingBlood->po_number,
          $user->id
        ),
        'deleted_at' => now(),
      ]);

      DB::commit();

      globalLogger('info', "Data stock in deleted successfully!", [
        'id' => $incomingBlood->id,
        'deleted_by' => Auth::user()->id,
      ], 200, 'newincomingblooddelete');

      return response()->json([
        'message' => "Data stock in deleted successfully!",
        'data' => $incomingBlood
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      globalLogger('error', "Data stock in failed to delete!", [
        'data' => $id,
        'deleted_by' => Auth::user()->id,
        'error' => $e->getMessage(),
      ], 500, 'newincomingblooddelete');

      return response()->json(['message' => "Data stock in failed to delete!"], 500);
    }
  }
  // ---------- Fungsi untuk delete data :end ----------

  // ---------- Fungsi untuk restore data :begin ----------
  public function restoreDataStockIn(string $id)
  {
    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      $user = Auth::user();
      $incomingBlood = IncomingBlood::onlyTrashed()->where('public_id', $id)->first();

      if (!$incomingBlood) {
        return response()->json(['message' => 'Data incoming blood not found in the trash'], 404);
      }

      $bloodStocks = BloodPack::onlyTrashed()->where('incoming_blood_id', $incomingBlood->id)->get();

      if ($bloodStocks->isEmpty()) {
        return response()->json(['message' => 'Data blood pack not found'], 404);
      }

      $incomingData = $incomingBlood->toArray();
      $bloodStockData = $bloodStocks->toArray();

      $bloodStocks->each->restore();
      $incomingBlood->restore();

      // ---------- Insert Incoming Blood Log Activity ----------
      IncomingBloodLogActivity::create([
        'incoming_blood_public_id' => $incomingBlood->public_id,
        'po_number' => $incomingBlood->po_number,
        'batch_number' => $incomingBlood->batch_number,
        'incoming_data' => $incomingData,
        'blood_stock_data' => $bloodStockData,
        'status' => IncomingBloodLogActivityStatus::INCOMING_RESTORED,
        'created_by_user_name' => $user->name,
        'description' => generateIncomingLogDescription(
          IncomingBloodLogActivityStatus::INCOMING_RESTORED,
          $incomingBlood->po_number,
          $user->id
        ),
      ]);

      DB::commit();

      globalLogger('info', "Data stock in restored successfully!", [
        'id' => $incomingBlood->id,
        'restored_by' => Auth::user()->id,
      ], 200, 'newincomingbloodrestore');

      return response()->json([
        'message' => "Data stock in restored successfully!",
        'data' => $incomingBlood
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      globalLogger('error', "Data stock in failed to restore!", [
        'data' => $id,
        'error' => $e->getMessage(),
        'restored_by' => Auth::user()->id,
      ], 500, 'newincomingbloodrestore');

      return response()->json(['message' => "Data stock in failed to restore!"], 500);
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

        $last = BloodStock::where('bag_number_lica', 'like', "{$prefix}%")
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

  // ---------- Helper: parse tanggal dari nilai excel :begin ----------
  /**
   * Tangani 3 kemungkinan format tanggal dari excel:
   * 1. Angka serial excel (misal: 46357) → konversi via PhpSpreadsheet
   * 2. Object DateTime (ketika openpyxl/PhpSpreadsheet sudah parse)
   * 3. String (d-m-Y, Y-m-d, atau format lain yang Carbon bisa parse)
   */
  private function parseExcelDate(mixed $value): string
  {
    if (is_numeric($value)) {
      return Carbon::instance(
        Date::excelToDateTimeObject((float) $value)
      )->toDateString();
    }

    if ($value instanceof \DateTime || $value instanceof \DateTimeInterface) {
      return Carbon::instance($value)->toDateString();
    }

    try {
      return Carbon::createFromFormat('d-m-Y', (string) $value)->toDateString();
    } catch (\Exception) {
      return Carbon::parse((string) $value)->toDateString();
    }
  }
  // ---------- Helper: parse tanggal dari nilai excel :end ----------

  // ---------- Helper: parse nilai serologis dari excel :begin ----------
  /**
   * Konversi nilai kolom HIV/HCV/HBSAG/Syphilis ke boolean.
   *
   * Template menggunakan "NR" (Non-Reactive) dan "R" (Reactive).
   * Nilai yang dianggap TRUE (reaktif/positif):
   *   "R", "r", "Reactive", "reactive", "REACTIVE", "1", "true", "yes", "Y"
   * Semua nilai lain (termasuk "NR", null, kosong) dianggap FALSE.
   */
  private function parseSerologicalValue(mixed $value): bool
  {
    if (is_null($value) || $value === '') {
      return false;
    }

    if (is_bool($value)) {
      return $value;
    }

    if (is_numeric($value)) {
      return (bool) $value;
    }

    $normalized = strtolower(trim((string) $value));

    return in_array($normalized, ['r', 'reactive', '1', 'true', 'yes', 'y'], true);
  }
  // ---------- Helper: parse nilai serologis dari excel :end ----------

  // ---------- Helper: baca file excel ke collection :begin ----------
  private function readExcelFile(\Illuminate\Http\UploadedFile $file): \Illuminate\Support\Collection
  {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, false);

    // Buang baris pertama (header), wrap tiap row jadi Collection
    return collect(array_values(array_slice($rows, 1)))
      ->map(fn($row) => collect($row));
  }
  // ---------- Helper: baca file excel ke collection :end ----------

  // ---------- Helper: untuk menerima dan menerapkan filter tanggal pada data :begin ----------
  protected function applyDateFilter(Builder $query, Request $request)
  {
    $start = $request->start_date;
    $end = $request->end_date;

    if ($start && $end) {
      try {
        $startDate = Carbon::createFromFormat('d-m-Y', $start)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y', $end)->endOfDay();
        $table = $query->getModel()->getTable();

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
