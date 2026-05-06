<?php

namespace App\Services\Inventory;

use App\Enums\IncomingBloodLogActivityStatus;
use App\Enums\IncomingBloodStatus;
use App\Enums\OrderBloodStatus;
use App\Enums\OrderLogActivityStatus;
use App\Models\BloodPack;
use App\Models\IncomingBlood;
use App\Models\IncomingBloodDetail;
use App\Models\IncomingBloodLogActivity;
use App\Models\OrderBlood;
use App\Models\OrderLogActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StockInAddService
{
  // ---------- Fungsi untuk menambahkan data order baru :begin ----------
  public function insertIncomingStockByManual(Request $request)
  {
    DB::beginTransaction();
    try {
      $user = Auth::user();
      $bloodDataItems = $request->input('blood_data', []);

      // ---------- Validasi tanggal & duplikat internal ----------
      $validationError = $this->validateBloodDates($bloodDataItems);
      if ($validationError) {
        return response()->json(['message' => $validationError], 422);
      }

      // ---------- Cek duplikat bag number di database ----------
      $duplicateError = $this->checkDuplicateBagNumbers(
        collect($bloodDataItems)->pluck('bag_number')->all()
      );
      if ($duplicateError) {
        DB::rollBack();
        return $duplicateError;
      }

      // ---------- Ambil order beserta relasinya ----------
      $orderBlood = OrderBlood::with(['vendors', 'orderBloodDetails'])
        ->where('po_number', $request->po_number)
        ->first();

      // ---------- Buat atau ambil incoming blood ----------
      $incomingBlood = $this->firstOrCreateIncomingBlood($request, $orderBlood, $user);

      // ---------- Siapkan blood pack map (public_id => id) ----------
      $bloodPackMap = BloodPack::whereIn('public_id', collect($bloodDataItems)->pluck('blood_pack_id'))
        ->pluck('id', 'public_id');

      // ---------- Bangun array detail untuk bulk insert ----------
      $incomingBloodDetails = $this->buildIncomingBloodDetails(
        $bloodDataItems,
        $incomingBlood->id,
        $bloodPackMap,
        'public_id'
      );

      // ---------- Bulk insert incoming blood details ----------
      IncomingBloodDetail::insert($incomingBloodDetails);

      // ---------- Tentukan & update status order ----------
      $orderStatus = $this->resolveOrderStatus(
        count($incomingBloodDetails),
        $orderBlood->total_quantity,
        $orderBlood->id,
      );
      $orderBlood->update(['status' => $orderStatus]);

      // ---------- Insert semua log activity ----------
      $this->insertIncomingBloodLog($incomingBlood, $request, $incomingBloodDetails, $user, 'manual');
      $this->insertOrderLog($orderBlood, $orderStatus, $user);

      DB::commit();

      globalLogger('info', 'New incoming blood data inserted successfully!', [
        'po_number' => $request->po_number,
        'batch_number' => $request->batch_number,
        'is_new' => $incomingBlood->wasRecentlyCreated,
        'total' => count($incomingBloodDetails),
        'inserted_by' => $user->id,
      ], 200, 'newincomingbloodadd');

      return response()->json([
        'message' => 'New incoming blood data inserted successfully!',
        'data' => $incomingBlood,
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      globalLogger('error', 'New incoming blood data failed to insert!', [
        'payload' => $request->all(),
        'error' => $e->getMessage(),
        'inserted_by' => Auth::id(),
      ], 500, 'newincomingbloodadd');

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
      $rows = $this->readExcelFile($request->file('excel_file'));

      if (!$rows || $rows->isEmpty()) {
        return response()->json(['message' => 'Excel file is empty or not readable!'], 422);
      }

      // ---------- Filter baris kosong & parse ----------
      $bloodDataItems = $this->parseExcelRows($rows);
      if ($bloodDataItems instanceof \Illuminate\Http\JsonResponse) {
        return $bloodDataItems;
      }

      // ---------- Validasi tanggal & duplikat internal ----------
      $validationError = $this->validateBloodDates($bloodDataItems);
      if ($validationError) {
        DB::rollBack();
        return response()->json(['message' => $validationError], 422);
      }

      // ---------- Cek duplikat bag number di database ----------
      $duplicateError = $this->checkDuplicateBagNumbers(
        collect($bloodDataItems)->pluck('bag_number')->all()
      );
      if ($duplicateError) {
        DB::rollBack();
        return $duplicateError;
      }

      // ---------- Ambil order beserta relasinya ----------
      $orderBlood = OrderBlood::with(['vendors', 'orderBloodDetails'])
        ->where('po_number', $request->po_number)
        ->first();

      // ---------- Buat atau ambil incoming blood ----------
      $incomingBlood = $this->firstOrCreateIncomingBlood($request, $orderBlood, $user = Auth::user());

      // ---------- Siapkan blood pack map (composite key => model) ----------
      $bloodPackMap = BloodPack::all()->keyBy(function ($item) {
        return strtoupper($item->blood_group->value) . '|' .
          strtoupper($item->blood_rhesus) . '|' .
          strtoupper($item->blood_component->value);
      });

      // ---------- Bangun array detail untuk bulk insert ----------
      $incomingBloodDetails = $this->buildIncomingBloodDetails(
        $bloodDataItems,
        $incomingBlood->id,
        $bloodPackMap,
        'composite_key'
      );

      if ($incomingBloodDetails instanceof \Illuminate\Http\JsonResponse) {
        return $incomingBloodDetails;
      }

      // ---------- Bulk insert incoming blood details ----------
      IncomingBloodDetail::insert($incomingBloodDetails);

      // ---------- Tentukan & update status order ----------
      $orderStatus = $this->resolveOrderStatus(
        count($incomingBloodDetails),
        $orderBlood->total_quantity,
        $orderBlood->id,
      );
      $orderBlood->update(['status' => $orderStatus]);

      // ---------- Insert semua log activity ----------
      $this->insertIncomingBloodLog($incomingBlood, $request, $incomingBloodDetails, $user, 'excel');
      $this->insertOrderLog($orderBlood, $orderStatus, $user);

      DB::commit();

      globalLogger('info', 'New incoming blood data via Excel inserted successfully!', [
        'po_number' => $request->po_number,
        'batch_number' => $request->batch_number,
        'total' => count($incomingBloodDetails),
        'inserted_by' => $user->id,
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

  // ==========================================================================
  // PRIVATE HELPERS
  // ==========================================================================

  // ---------- Helper: cek duplikat bag number di database :begin ----------
  private function checkDuplicateBagNumbers(array $bagNumbers): ?\Illuminate\Http\JsonResponse
  {
    $duplicates = IncomingBloodDetail::whereIn('bag_number', $bagNumbers)
      ->pluck('bag_number')
      ->all();

    if (!empty($duplicates)) {
      return response()->json([
        'message'    => 'Duplicate bag number detected!',
        'duplicates' => $duplicates,
      ], 422);
    }

    return null;
  }
  // ---------- Helper: cek duplikat bag number di database :end ----------

  // ---------- Helper: firstOrCreate IncomingBlood :begin ----------
  private function firstOrCreateIncomingBlood(Request $request, OrderBlood $orderBlood, ?User $user): IncomingBlood
  {
    return IncomingBlood::firstOrCreate(
      [
        'po_number' => $request->po_number,
        'batch_number' => $request->batch_number,
      ],
      [
        'order_blood_id' => $orderBlood->id,
        'status' => IncomingBloodStatus::STOCK_REGISTERED,
        'received_by_user_id' => null,
        'received_at' => null,
        'registered_by_user_id' => $user->id,
      ]
    );
  }
  // ---------- Helper: firstOrCreate IncomingBlood :end ----------

  // ---------- Helper: build incoming blood details array untuk bulk insert :begin ----------
  private function buildIncomingBloodDetails(
    array $items,
    int $incomingBloodId,
    $bloodPackMap,
    string $mode
  ): array|\Illuminate\Http\JsonResponse {
    $details = [];
    $now = now();

    foreach ($items as $item) {
      // ---------- Resolve blood pack id berdasarkan mode ----------
      if ($mode === 'public_id') {
        $bloodPackId = $bloodPackMap[$item['blood_pack_id']] ?? null;
        $errorKey    = $item['blood_pack_id'];
      } else {
        $bloodPack   = $bloodPackMap->get($item['blood_pack_key']);
        $bloodPackId = $bloodPack?->id;
        $errorKey    = $item['blood_pack_key'];
      }

      if (!$bloodPackId) {
        return response()->json([
          'message' => "Blood pack not found for: {$errorKey}",
        ], 422);
      }

      $details[] = [
        'public_id' => (string) \Illuminate\Support\Str::uuid(),
        'incoming_blood_id' => $incomingBloodId,
        'bag_number' => $item['bag_number'],
        'blood_pack_id' => $bloodPackId,
        'blood_volume' => $item['blood_volume'],
        'aftap_date' => Carbon::createFromFormat('d-m-Y', $item['aftap_date'])->toDateString(),
        'process_date' => Carbon::createFromFormat('d-m-Y', $item['process_date'])->toDateString(),
        'expiry_date' => Carbon::createFromFormat('d-m-Y', $item['expiry_date'])->toDateString(),
        'is_hiv' => (bool) ($item['is_hiv'] ?? false),
        'is_hbsag' => (bool) ($item['is_hbsag'] ?? false),
        'is_hcv' => (bool) ($item['is_hcv'] ?? false),
        'is_syphilis' => (bool) ($item['is_syphilis'] ?? false),
        'created_at' => $now,
        'updated_at' => $now,
      ];
    }

    return $details;
  }
  // ---------- Helper: build incoming blood details array untuk bulk insert :end ----------

  // ---------- Helper: tentukan status order berdasarkan jumlah yang diinsert :begin ----------
  private function resolveOrderStatus(int $newlyInsertedCount, int $totalQuantity, int $orderBloodId): OrderBloodStatus
  {
    // ---------- Hitung total yang sudah ada di DB sebelum batch ini ----------
    $alreadyInsertedCount = IncomingBloodDetail::whereHas('incomingBloods', function ($q) use ($orderBloodId) {
      $q->where('order_blood_id', $orderBloodId);
    })->count();

    $cumulativeCount = $alreadyInsertedCount + $newlyInsertedCount;

    return $cumulativeCount >= $totalQuantity
      ? OrderBloodStatus::ALL_ORDER_STOCK_REGISTERED
      : OrderBloodStatus::SOME_ORDER_STOCK_REGISTERED;
  }
  // ---------- Helper: tentukan status order berdasarkan jumlah yang diinsert :end ----------

  // ---------- Helper: insert incoming blood log activity :begin ----------
  private function insertIncomingBloodLog(
    IncomingBlood $incomingBlood,
    Request $request,
    array $incomingBloodDetails,
    ?User $user,
    ?string $method
  ): void {
    $status = match ($method) {
      'excel' => IncomingBloodLogActivityStatus::INCOMING_CREATED_BY_EXCEL,
      default => IncomingBloodLogActivityStatus::INCOMING_CREATED_BY_MANUAL,
    };

    IncomingBloodLogActivity::create([
      'incoming_blood_public_id' => $incomingBlood->public_id,
      'po_number' => $request->po_number,
      'batch_number' => $request->batch_number,
      'incoming_data' => $incomingBlood->toArray(),
      'blood_data' => $incomingBloodDetails,
      'status' => $status,
      'created_by_user_name' => $user->name,
      'description' => generateIncomingLogDescription(
        $status,
        $request->po_number,
        $user->id
      ),
    ]);
  }
  // ---------- Helper: insert incoming blood log activity :end ----------

  // ---------- Helper: insert order log activity :begin ----------
  private function insertOrderLog(OrderBlood $orderBlood, OrderBloodStatus $status, ?User $user): void
  {
    $logStatus = $status === OrderBloodStatus::ALL_ORDER_STOCK_REGISTERED
      ? OrderLogActivityStatus::ALL_ORDER_STOCK_REGISTERED
      : OrderLogActivityStatus::SOME_ORDER_STOCK_REGISTERED;

    OrderLogActivity::create([
      'po_number' => $orderBlood->po_number,
      'vendor_name' => $orderBlood->vendors->name,
      'order_data' => $orderBlood->toArray(),
      'order_blood_data' => $orderBlood->orderBloodDetails
        ->map(fn($d) => $d->toArray())
        ->toArray(),
      'created_by_user_name' => $user->name,
      'status' => $logStatus,
      'description' => generateOrderLogDescription(
        $logStatus,
        $orderBlood->po_number,
        $user->id
      ),
      'timestamp' => $orderBlood->created_at,
    ]);
  }
  // ---------- Helper: insert order log activity :end ----------

  // ---------- Helper: parse baris excel ke array blood data items :begin ----------
  private function parseExcelRows(\Illuminate\Support\Collection $rows): array|\Illuminate\Http\JsonResponse
  {
    $bloodDataItems = [];

    $dataRows = $rows->values()->filter(
      fn($row) => $row->filter(fn($val) => !is_null($val) && $val !== '')->isNotEmpty()
    );

    foreach ($dataRows as $rowIndex => $row) {
      $excelRowNumber = $rowIndex + 2;

      $bagNumber      = trim((string) ($row->get(0) ?? ''));
      $bloodGroup     = trim((string) ($row->get(1) ?? ''));
      $rhesus         = trim((string) ($row->get(2) ?? ''));
      $bloodComponent = trim((string) ($row->get(3) ?? ''));
      $volume         = $row->get(4);
      $aftapRaw       = $row->get(5);
      $expiryRaw      = $row->get(6);
      $processRaw     = $row->get(7);

      if (
        empty($bagNumber) || empty($bloodGroup) || empty($rhesus) ||
        empty($bloodComponent) || is_null($volume) ||
        is_null($aftapRaw) || is_null($expiryRaw) || is_null($processRaw)
      ) {
        return response()->json([
          'message' => "Row {$excelRowNumber}: required field missing",
        ], 422);
      }

      $bloodDataItems[] = [
        'bag_number' => $bagNumber,
        'blood_pack_key' => strtoupper("{$bloodGroup}|{$rhesus}|{$bloodComponent}"),
        'blood_volume' => $volume,
        'aftap_date' => Carbon::parse($this->parseExcelDate($aftapRaw))->format('d-m-Y'),
        'process_date' => Carbon::parse($this->parseExcelDate($processRaw))->format('d-m-Y'),
        'expiry_date' => Carbon::parse($this->parseExcelDate($expiryRaw))->format('d-m-Y'),
        'is_hiv' => $this->parseSerologicalValue($row->get(8)),
        'is_hcv' => $this->parseSerologicalValue($row->get(9)),
        'is_hbsag' => $this->parseSerologicalValue($row->get(10)),
        'is_syphilis' => $this->parseSerologicalValue($row->get(11)),
      ];
    }

    return $bloodDataItems;
  }
  // ---------- Helper: parse baris excel ke array blood data items :end ----------

  // ---------- Helper: parse tanggal dari nilai excel :begin ----------
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
  private function parseSerologicalValue(mixed $value): bool
  {
    if (is_null($value) || $value === '') return false;
    if (is_bool($value)) return $value;
    if (is_numeric($value)) return (bool) $value;

    return in_array(strtolower(trim((string) $value)), ['r', 'reactive', '1', 'true', 'yes', 'y'], true);
  }
  // ---------- Helper: parse nilai serologis dari excel :end ----------

  // ---------- Helper: baca file excel ke collection :begin ----------
  private function readExcelFile(\Illuminate\Http\UploadedFile $file): \Illuminate\Support\Collection
  {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
    $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

    return collect(array_values(array_slice($rows, 1)))
      ->map(fn($row) => collect($row));
  }
  // ---------- Helper: baca file excel ke collection :end ----------

  // ---------- Helper: untuk memvalidasi tanggal pada blood data :begin ----------
  private function validateBloodDates(array $bloodDataItems): ?string
  {
    $today = Carbon::today()->startOfDay();
    $bagNumbers = [];

    foreach ($bloodDataItems as $index => $item) {
      $row = $index + 1;

      if (in_array($item['bag_number'], $bagNumbers)) {
        return "Duplicate bag number at row {$row}";
      }
      $bagNumbers[] = $item['bag_number'];

      try {
        $aftap = Carbon::createFromFormat('d-m-Y', $item['aftap_date'])->startOfDay();
        $process = Carbon::createFromFormat('d-m-Y', $item['process_date'])->startOfDay();
        $expiry = Carbon::createFromFormat('d-m-Y', $item['expiry_date'])->startOfDay();

        if ($aftap->gte($today)) return "Aftap date at row {$row} must be before today";
        if ($process->gte($today)) return "Process date at row {$row} must be before today";
        if ($process->lt($aftap)) return "Process date at row {$row} cannot be before aftap date";
        if ($expiry->lte($today)) return "Expiry date at row {$row} must be greater than today";
        if ($expiry->lt($aftap)) return "Expiry date at row {$row} cannot be before aftap date";
        if ($expiry->lt($process)) return "Expiry date at row {$row} cannot be before process date";
      } catch (\Exception) {
        return "Invalid date format at row {$row}";
      }
    }

    return null;
  }
  // ---------- Helper: untuk memvalidasi tanggal pada blood data :end ----------
}
