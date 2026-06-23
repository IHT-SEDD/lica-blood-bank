<?php

namespace App\Services\Inventory\StockIn;

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
      if (!$orderBlood) {
        DB::rollBack();
        return response()->json(['message' => "PO Number {$request->po_number} tidak ditemukan."], 404);
      }

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
      if ($incomingBloodDetails instanceof \Illuminate\Http\JsonResponse) {
        DB::rollBack();
        return $incomingBloodDetails;
      }

      // ---------- Bulk insert incoming blood details ----------
      IncomingBloodDetail::insert($incomingBloodDetails);

      // ---------- Tentukan & update status order ----------
      $orderStatus = $this->resolveOrderStatus(
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
      $user = Auth::user();

      $rows = $this->readExcelFile($request->file('excel_file'));
      if (!$rows || $rows->isEmpty()) {
        return response()->json(['message' => 'File excel kosong atau tidak bisa dibaca!'], 422);
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
      if (!$orderBlood) {
        DB::rollBack();
        return response()->json(['message' => "PO Number {$request->po_number} tidak ditemukan."], 404);
      }

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
  private function checkDuplicateBagNumbers(array $bagNumbers): ?\Illuminate\Http\JsonResponse
  {
    $duplicates = IncomingBloodDetail::whereIn('bag_number', $bagNumbers)
      ->pluck('bag_number')
      ->all();

    if (!empty($duplicates)) {
      return response()->json([
        'message' => 'Terdapat nomor kantong yang duplikat!',
        'duplicates' => $duplicates,
      ], 422);
    }

    return null;
  }
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
        $errorKey = $item['blood_pack_id'];
      } else {
        $bloodPack = $bloodPackMap->get($item['blood_pack_key']);
        $bloodPackId = $bloodPack?->id;
        $errorKey = $item['blood_pack_key'];
      }

      if (!$bloodPackId) {
        return response()->json([
          'message' => "Kantong darah tidak ditemukan untuk: {$errorKey}",
        ], 422);
      }

      $details[] = [
        'public_id' => (string) \Illuminate\Support\Str::uuid(),
        'incoming_blood_id' => $incomingBloodId,
        'bag_number' => $item['bag_number'],
        'blood_pack_id' => $bloodPackId,
        'blood_volume' => $item['blood_volume'],
        'aftap_date' => Carbon::createFromFormat('d-m-Y H:i', $item['aftap_date'])->toDateTimeString(),
        'process_date' => !empty($item['process_date'])
          ? Carbon::createFromFormat('d-m-Y H:i', $item['process_date'])
          : null,
        'expiry_date' => Carbon::createFromFormat('d-m-Y H:i', $item['expiry_date'])->toDateTimeString(),
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
  private function resolveOrderStatus(int $totalQuantity, int $orderBloodId): OrderBloodStatus
  {
    $totalInserted = IncomingBloodDetail::whereHas('incomingBloods', function ($q) use ($orderBloodId) {
      $q->where('order_blood_id', $orderBloodId);
    })->count();

    return $totalInserted >= $totalQuantity
      ? OrderBloodStatus::ALL_ORDER_STOCK_REGISTERED
      : OrderBloodStatus::SOME_ORDER_STOCK_REGISTERED;
  }
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
  private function parseExcelRows(\Illuminate\Support\Collection $rows): array|\Illuminate\Http\JsonResponse
  {
    $bloodDataItems = [];

    $dataRows = $rows->values()->filter(
      fn($row) => $row->filter(fn($val) => !is_null($val) && $val !== '')->isNotEmpty()
    );

    foreach ($dataRows as $rowIndex => $row) {
      $excelRowNumber = $rowIndex + 2;

      $bagNumber = trim((string) ($row->get(0) ?? ''));
      $bloodGroup = trim((string) ($row->get(1) ?? ''));
      $rhesus = trim((string) ($row->get(2) ?? ''));
      $bloodComponent = trim((string) ($row->get(3) ?? ''));
      $volume = $row->get(4);
      $aftapRaw = $row->get(5);
      $expiryRaw = $row->get(6);
      $processRaw = $row->get(7);

      if (
        empty($bagNumber) || empty($bloodGroup) || empty($rhesus) ||
        empty($bloodComponent) || is_null($volume) ||
        is_null($aftapRaw) || is_null($expiryRaw)
      ) {
        return response()->json([
          'message' => "Baris {$excelRowNumber}: tidak boleh kosong",
        ], 422);
      }

      try {
        $bloodDataItems[] = [
          'bag_number' => $bagNumber,
          'blood_pack_key' => strtoupper("{$bloodGroup}|{$rhesus}|{$bloodComponent}"),
          'blood_volume' => $volume,
          'aftap_date' => Carbon::parse($this->parseExcelDate($aftapRaw))->format('d-m-Y H:i'),
          'process_date' => !empty($processRaw)
            ? Carbon::parse($this->parseExcelDate($processRaw))->format('d-m-Y H:i')
            : null,
          'expiry_date' => Carbon::parse($this->parseExcelDate($expiryRaw))->format('d-m-Y H:i'),
          'is_hiv' => $this->parseSerologicalValue($row->get(8)),
          'is_hcv' => $this->parseSerologicalValue($row->get(9)),
          'is_hbsag' => $this->parseSerologicalValue($row->get(10)),
          'is_syphilis' => $this->parseSerologicalValue($row->get(11)),
        ];
      } catch (\InvalidArgumentException $e) {
        return response()->json([
          'message' => "Baris {$excelRowNumber}: " . $e->getMessage(),
        ], 422);
      }
    }

    return $bloodDataItems;
  }
  private function parseExcelDate(mixed $value): string
  {
    if (is_numeric($value)) {
      return Carbon::instance(
        Date::excelToDateTimeObject((float) $value)
      )->toDateTimeString();
    }

    if ($value instanceof \DateTime || $value instanceof \DateTimeInterface) {
      return Carbon::instance($value)->toDateTimeString();
    }

    try {
      return Carbon::createFromFormat('d-m-Y H:i', (string) $value)->toDateTimeString();
    } catch (\Exception) {
      return Carbon::parse((string) $value)->toDateTimeString();
    }
  }
  private function parseSerologicalValue(mixed $value): bool
  {
    if (is_null($value) || $value === '') return false;
    if (is_bool($value)) return $value;
    if (is_numeric($value)) return (bool) $value;

    $normalized = strtolower(trim((string) $value));

    $truthy = ['r', 'reactive', '1', 'true', 'yes', 'y'];
    $falsy  = ['nr', 'non-reactive', 'non reactive', '0', 'false', 'no', 'n', '-'];

    if (in_array($normalized, $truthy, true)) return true;
    if (in_array($normalized, $falsy, true))  return false;

    throw new \InvalidArgumentException("Nilai serologi tidak dikenali: '{$value}'");
  }
  private function readExcelFile(\Illuminate\Http\UploadedFile $file): \Illuminate\Support\Collection
  {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
    $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

    return collect(array_values(array_slice($rows, 1)))
      ->map(fn($row) => collect($row));
  }
  private function validateBloodDates(array $bloodDataItems): ?string
  {
    $today = Carbon::today()->startOfDay();
    $bagNumbers = [];

    foreach ($bloodDataItems as $index => $item) {
      $row = $index + 1;

      if (in_array($item['bag_number'], $bagNumbers)) {
        return "Terdapat nomor kantong duplikat pada baris {$row}";
      }
      $bagNumbers[] = $item['bag_number'];

      try {
        $aftap = Carbon::createFromFormat('d-m-Y H:i', $item['aftap_date']);
        $process = !empty($item['process_date'])
          ? Carbon::createFromFormat('d-m-Y H:i', $item['process_date'])
          : null;
        $expiry  = Carbon::createFromFormat('d-m-Y H:i', $item['expiry_date']);

        if ($expiry->lt($aftap)) return "Tanggal expire pada baris {$row} harus setelah tanggal aftap";
        if ($process && $expiry->lt($process)) {
          return "Tanggal expire pada baris {$row} harus setelah tanggal proses";
        }
      } catch (\Exception) {
        return "Format tanggal tidak sesuai pada baris {$row}";
      }
    }

    return null;
  }
}
