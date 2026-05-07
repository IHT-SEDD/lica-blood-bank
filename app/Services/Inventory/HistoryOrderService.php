<?php

namespace App\Services\Inventory;

use App\Enums\OrderBloodStatus;
use App\Enums\OrderLogActivityStatus;
use App\Models\BloodPack;
use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use App\Models\OrderLogActivity;
use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class HistoryOrderService
{
  const CACHE_ORDER_BY_ID_KEY = "order_and_log_data_by_id";
  const CACHE_ORDER_BY_PO_KEY = "order_and_log_data_by_PO";

  // ---------- Fungsi untuk menampilkan data history order ke tabel :begin ----------
  public function historyOrderTable(Request $request)
  {
    // ---------- Ambil data ----------
    $query = OrderBlood::withTrashed()
      ->with([
        'vendors:id,public_id,name',
        'orderBloodDetails:id,public_id,order_blood_id,blood_pack_id',
        'orderBloodDetails.bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
      ]);

    // ---------- Terapkan filter untuk tanggal pada data ----------
    $this->applyDateFilter($query, $request);

    // ---------- Filter berdasarkan vendor ----------
    if ($request->filled('vendor_id')) {
      $query->whereHas('vendors', function ($q) use ($request) {
        $q->where('public_id', $request->vendor_id);
      });
    }

    // ---------- Filter berdasarkan status ----------
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    // ---------- Filter berdasarkan blood_group dari detail ----------
    if ($request->filled('blood_group')) {
      $query->whereHas('orderBloods.bloodPacks', function ($q) use ($request) {
        $q->where('blood_group', $request->blood_group);
      });
    }

    // ---------- Handle search pada kolom data ----------
    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('po_number', 'like', "%{$search}%")
          ->orWhereHas('vendors', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
          })
          ->orWhereHas('orderBloods.bloodPacks', function ($q) use ($search) {
            $q->where('blood_group', 'like', "%{$search}%");
          });
      });
    }

    // ---------- Urutkan data ----------
    if ($request->filled('sort_by')) {
      $query->orderBy(
        $request->sort_by,
        $request->sort_dir ?? 'asc'
      );
    } else {
      $query->latest();
    }

    // ---------- Tampilkan data ke tabel frontend ----------
    return $query->paginate($request->get('per_page', 10));
  }
  // ---------- Fungsi untuk menampilkan data history order ke tabel :end ----------

  // ---------- Fungsi untuk menambahkan data order baru :begin ----------
  public function insertNewOrder(Request $request, ?string $draft)
  {
    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      // ---------- Hitung total quantity dari semua blood data ----------
      $totalQuantity = collect($request->blood_data)
        ->sum(fn($item) => (int) $item['quantity']);

      // ---------- Validasi PO Number ----------
      $poNumberExists = OrderBlood::where('po_number', $request->po_number)->exists();
      $poNumber = (!empty($request->po_number) && !$poNumberExists) ? $request->po_number : $this->generatePoNumber();

      // ---------- Ambil data vendor ----------
      $vendor = Vendor::where('public_id', $request->vendor_id)->first();

      $vendorId = $vendor?->id;
      $vendorName = $vendor?->name;

      // ---------- Ambil data user ----------
      $user = Auth::user();

      // ---------- Ambil status order ----------
      $status = $draft === 'draft' ? OrderBloodStatus::DRAFT : OrderBloodStatus::ORDER_CREATED;

      // ---------- Insert ke tabel order_bloods ----------
      $newOrderData = OrderBlood::create([
        'vendor_id' => $vendorId,
        'po_number' => $poNumber,
        'total_quantity' => $totalQuantity,
        'description' => $request->description ?? NULL,
        'status' => $status,
        'ordered_by_user_id' => $user->id,
      ]);

      // ---------- Insert detail per blood data ----------
      $orderBloodDetails = [];

      foreach ($request->blood_data as $item) {
        $bloodPack = BloodPack::where('public_id', $item['blood_pack_id'])->firstOrFail();

        $detail = OrderBloodDetail::create([
          'order_blood_id' => $newOrderData->id,
          'blood_pack_id' => $bloodPack->id,
          'note' => $item['note'],
          'quantity' => $item['quantity'],
        ]);

        $orderBloodDetails[] = $detail;
      }

      // ---------- Ambil status order log ----------
      $statusLog = $draft === 'draft'
        ? OrderLogActivityStatus::DRAFT_CREATED
        : OrderLogActivityStatus::ORDER_CREATED;

      // ---------- Insert Order Log Activity ----------
      OrderLogActivity::create([
        'po_number' => $poNumber,
        'vendor_name' => $vendorName,
        'order_data' => $newOrderData->toArray(),
        'order_blood_data' => collect($orderBloodDetails)
          ->map(fn($d) => $d->toArray())
          ->toArray(),
        'created_by_user_name' => $user->name,
        'status' => $statusLog,
        'description' => generateOrderLogDescription(
          $statusLog,
          $poNumber,
          $user->id
        ),
        'timestamp' => $newOrderData->created_at,
      ]);

      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger('info', 'New order data inserted succesfully!', [
        'id' => $newOrderData->id,
        'payload' => $newOrderData,
      ], 200, 'neworderadd');

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => 'New order data inserted succesfully!',
        'data' => $newOrderData
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger('error', 'New order data failed to insert!', [
        'payload' => $request->all(),
        'error' => $e->getMessage(),
      ], 500, 'neworderadd');

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => 'New order data failed to insert!',
      ], 500);
    }
  }
  // ---------- Fungsi untuk menambahkan data order baru :end ----------

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

  // ---------- Fungsi untuk membuat po number :begin ----------
  public function generatePoNumber(): string
  {
    $year = now()->format('Y');
    $last = OrderBlood::where('po_number', 'like', "P{$year}OB%")
      ->lockForUpdate()
      ->orderByDesc('po_number')
      ->first();
    $nextNumber = $last
      ? ((int) substr($last->po_number, -6) + 1)
      : 1;
    $poNumber = 'P' . $year . 'OB' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

    // ---------- Lempar data ke frontend ----------
    return $poNumber;
  }
  // ---------- Fungsi untuk membuat po number :end ----------

  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :begin ----------
  public function getDataOrderAndLogById(string $id)
  {
    $cacheKey = self::CACHE_ORDER_BY_ID_KEY . ":{$id}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($id) {

      $order = OrderBlood::where('public_id', $id)
        ->with(['orderBloodDetails', 'orderBloodDetails.bloodPacks', 'vendors', 'users.roles'])
        ->firstOrFail();

      $log = OrderLogActivity::where('po_number', $order->po_number)
        ->firstOrFail();

      return [
        'order' => $order,
        'log' => $log,
      ];
    });
  }
  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :end ----------

  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :begin ----------
  public function getDataOrderByPO(string $poNumber)
  {
    $cacheKey = self::CACHE_ORDER_BY_PO_KEY . ":{$poNumber}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($poNumber) {
      $order = OrderBlood::where('po_number', $poNumber)
        ->with(['orderBloodDetails', 'vendors', 'users.roles'])
        ->firstOrFail();

      return $order;
    });
  }
  // ---------- Fungsi untuk mengambil data order & log berdasarkan id :end ----------

  // ---------- Fungsi untuk mengambil data order berdasarkan id :begin ----------
  public function getDataOrderById(string $id)
  {
      $order = OrderBlood::where('public_id', $id)
        ->with(['orderBloodDetails', 'vendors', 'users.roles'])
        ->firstOrFail();

      return $order;
  }
  // ---------- Fungsi untuk mengambil data order berdasarkan id :end ----------

  // ---------- Clear Cache ----------
  public function clearOrderCache(string $id, string $poNumber)
  {
    Cache::forget(self::CACHE_ORDER_BY_ID_KEY . ":{$id}");
    Cache::forget(self::CACHE_ORDER_BY_PO_KEY . ":{$poNumber}");
  }

  // ---------- Fungsi untuk generate PO File :begin ----------
  public function generatePoFile(string $poNumber)
  {
    DB::beginTransaction();
    try {
      $user = Auth::user();

      // ---------- Ambil data order ----------
      $order = OrderBlood::where('po_number', $poNumber)
        ->with(['vendors', 'users', 'orderBloodDetails', 'orderBloodDetails.bloodPacks'])
        ->firstOrFail();

      $fileName = "PO_FILE-{$poNumber}.pdf";
      $directory = "history_order/po_file";
      $filePath = "{$directory}/{$fileName}";

      // ---------- Jika sudah ada, langsung download ----------
      if ($order->po_file_path && Storage::disk('public')->exists($order->po_file_path)) {
        $order->increment('po_file_download_count');

        $absolutePath = Storage::disk('public')->path($order->po_file_path);

        return response()->download($absolutePath, $fileName, [
          'Content-Type' => 'application/pdf',
          'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
      }

      // ---------- Pastikan direktori ada ----------
      Storage::disk('public')->makeDirectory($directory);

      // ---------- Generate PDF dari blade ----------
      $pdf = Pdf::loadView('pdf.history_order.po_file', [
        'order' => $order,
        'details' => $order->orderBloodDetails,
        'vendor' => $order->vendors,
      ])->setPaper('a4', 'portrait');

      $pdfContent = $pdf->output();

      // ---------- Simpan file ke storage ----------
      Storage::disk('public')->put($filePath, $pdfContent);

      // ---------- Update model OrderBlood ----------
      $order->update([
        'po_file_path' => $filePath,
        'po_file_name' => $fileName,
        'po_file_download_count' => 1,
      ]);

      // ---------- Clear cache agar data terbaru ----------
      $this->clearOrderCache($order->public_id, $poNumber);

      // ---------- Insert data to log ----------
      OrderLogActivity::create([
        'po_number' => $poNumber,
        'vendor_name' => $order->vendors->name,
        'order_data' => $order->toArray(),
        'order_blood_data' => null,
        'created_by_user_name' => $user->name,
        'status' => OrderLogActivityStatus::PO_FILE_GENERATED,
        'description' => generateOrderLogDescription(
          OrderLogActivityStatus::PO_FILE_GENERATED,
          $poNumber,
          $user->id
        ),
        'timestamp' => now(),
        'po_file_path' => $filePath,
        'po_file_name' => $fileName,
      ]);

      DB::commit();

      // ---------- Log sukses ----------
      globalLogger('info', 'PO File generated successfully!', [
        'po_number' => $poNumber,
        'file_path' => $filePath,
      ], 200, 'generatepofile');

      // ---------- Return sebagai download ----------
      return response()->download(
        Storage::disk('public')->path($filePath),
        $fileName,
        [
          'Content-Type' => 'application/pdf',
          'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]
      );
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger('error', 'PO File failed to generate!', [
        'po_number' => $poNumber,
        'error' => $e->getMessage(),
      ], 500, 'generatepofile');

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => 'New order data failed to insert!',
      ], 500);
    }
  }

  // ---------- Fungsi untuk preview PO File (tanpa simpan ke DB & storage) :begin ----------
  public function previewPoFile(string $poNumber)
  {
    // ---------- Ambil data order ----------
    $order = OrderBlood::where('po_number', $poNumber)
      ->with([
        'vendors',
        'users',
        'orderBloodDetails',
        'orderBloodDetails.bloodPacks',
      ])
      ->firstOrFail();

    // ---------- Generate PDF dari blade (tanpa simpan) ----------
    $pdf = Pdf::loadView('pdf.history_order.po_file', [
      'order' => $order,
      'details' => $order->orderBloodDetails,
      'vendor' => $order->vendors,
    ])->setPaper('a4', 'portrait');

    // ---------- Stream langsung ke browser (inline, bukan download) ----------
    return response($pdf->output(), 200, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'inline; filename="PREVIEW-' . $poNumber . '.pdf"',
    ]);
  }
  // ---------- Fungsi untuk preview PO File (tanpa simpan ke DB & storage) :end ----------

  // ---------- Fungsi untuk download PO File (wajib sudah ada, tidak generate baru) :begin ----------
  public function downloadPoFile(string $poNumber)
  {
    DB::beginTransaction();
    try {
      $user = Auth::user();

      // ---------- Ambil data order ----------
      $order = OrderBlood::where('po_number', $poNumber)->with('vendors')
        ->firstOrFail();

      // ---------- Validasi: file harus sudah pernah di-generate ----------
      if (!$order->po_file_path || !Storage::disk('public')->exists($order->po_file_path)) {
        return response()->json([
          'message' => 'PO File not found! Please generate the PO File first.',
        ], 404);
      }

      $fileName = $order->po_file_name ?? "PO_FILE-{$poNumber}.pdf";
      $absolutePath = Storage::disk('public')->path($order->po_file_path);

      // ---------- Increment download count ----------
      $order->increment('po_file_download_count');

      // ---------- Insert data to log ----------
      OrderLogActivity::create([
        'po_number' => $poNumber,
        'vendor_name' => $order->vendors->name,
        'order_data' => $order->toArray(),
        'order_blood_data' => null,
        'created_by_user_name' => $user->name,
        'status' => OrderLogActivityStatus::PO_FILE_DOWNLOADED,
        'description' => generateOrderLogDescription(
          OrderLogActivityStatus::PO_FILE_DOWNLOADED,
          $poNumber,
          $user->id
        ),
        'timestamp' => now(),
        'po_file_path' => $order->po_file_path,
        'po_file_name' => $fileName,
      ]);

      DB::commit();
      // ---------- Log aktivitas download ----------
      globalLogger('info', 'PO File downloaded successfully!', [
        'po_number' => $poNumber,
        'file_path' => $order->po_file_path,
        'download_count' => $order->po_file_download_count,
      ], 200, 'downloadpofile');

      return response()->download($absolutePath, $fileName, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger('error', 'PO File failed to download!', [
        'po_number' => $poNumber,
        'error' => $e->getMessage(),
      ], 500, 'downloadpofile');

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => 'PO File failed to download!',
      ], 500);
    }
  }
  // ---------- Fungsi untuk download PO File :end ----------

  // ---------- Fungsi untuk print PO File (wajib sudah ada, tidak generate baru) :begin ----------
  public function printPoFile(string $poNumber)
  {
    DB::beginTransaction();
    try {
      $user = Auth::user();

      // ---------- Ambil data order ----------
      $order = OrderBlood::where('po_number', $poNumber)->with('vendors')
        ->firstOrFail();

      // ---------- Validasi: file harus sudah pernah di-generate ----------
      if (!$order->po_file_path || !Storage::disk('public')->exists($order->po_file_path)) {
        return response()->json([
          'message' => 'PO File not found! Please generate the PO File first.',
        ], 404);
      }

      $fileName = $order->po_file_name ?? "PO_FILE-{$poNumber}.pdf";
      $absolutePath = Storage::disk('public')->path($order->po_file_path);

      // ---------- Increment print count ----------
      $order->increment('po_file_print_count');

      // ---------- Insert data to log ----------
      OrderLogActivity::create([
        'po_number' => $poNumber,
        'vendor_name' => $order->vendors->name,
        'order_data' => $order->toArray(),
        'order_blood_data' => null,
        'created_by_user_name' => $user->name,
        'status' => OrderLogActivityStatus::PO_FILE_PRINTED,
        'description' => generateOrderLogDescription(
          OrderLogActivityStatus::PO_FILE_PRINTED,
          $poNumber,
          $user->id
        ),
        'timestamp' => now(),
        'po_file_path' => $order->po_file_path,
        'po_file_name' => $fileName,
      ]);

      DB::commit();
      // ---------- Log aktivitas download ----------
      globalLogger('info', 'PO File printed successfully!', [
        'po_number' => $poNumber,
        'file_path' => $order->po_file_path,
        'print_count' => $order->po_file_print_count,
      ], 200, 'printpofile');

      return response()->download($absolutePath, $fileName, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger('error', 'PO File failed to print!', [
        'po_number' => $poNumber,
        'error' => $e->getMessage(),
      ], 500, 'printpofile');

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => 'PO File failed to print!',
      ], 500);
    }
  }
  // ---------- Fungsi untuk print PO File :end ----------

  // ---------- Fungsi untuk update data order :begin ----------
  public function updateDataOrder(Request $request, string $id)
  {
    DB::beginTransaction();
    try {
      $user = Auth::user();

      $order = OrderBlood::where('public_id', $id)
        ->with(['orderBloodDetails', 'vendors'])
        ->firstOrFail();

      // ---------- Hanya status draft / order_created yang boleh diedit ----------
      $editableStatuses = [
        OrderBloodStatus::DRAFT->value,
        OrderBloodStatus::ORDER_CREATED->value,
      ];
      if (!in_array($order->status->value, $editableStatuses)) {
        return response()->json(['message' => 'Order cannot be edited in current status!'], 422);
      }

      $changes = []; // Catat field yang berubah untuk log

      // ---------- Update field order (hanya yang dikirim & berubah) ----------
      if ($request->has('vendor_id')) {
        $vendor = Vendor::where('public_id', $request->vendor_id)->firstOrFail();
        if ($order->vendor_id !== $vendor->id) {
          $changes['vendor_id'] = ['old' => $order->vendor_id, 'new' => $vendor->id];
          $order->vendor_id = $vendor->id;
        }
      }

      if ($request->has('description') && $order->description !== $request->description) {
        $changes['description'] = ['old' => $order->description, 'new' => $request->description];
        $order->description = $request->description;
      }

      // ---------- Update blood details jika dikirim ----------
      if ($request->has('blood_data')) {
        // Hapus semua detail lama, insert ulang yang baru
        $oldDetails = $order->orderBloodDetails->toArray();

        $order->orderBloodDetails()->delete();

        $newDetails = [];
        $totalQuantity = 0;

        foreach ($request->blood_data as $item) {
          $bloodPack = BloodPack::where('id', $item['blood_pack_id'])->firstOrFail();

          $detail = OrderBloodDetail::create([
            'order_blood_id' => $order->id,
            'blood_pack_id' => $bloodPack->id,
            'note' => $item['note'] ?? null,
            'quantity' => $item['quantity'],
          ]);

          $newDetails[] = $detail->toArray();
          $totalQuantity += (int) $item['quantity'];
        }

        $changes['blood_details'] = ['old' => $oldDetails, 'new' => $newDetails];
        $order->total_quantity = $totalQuantity;
      }

      // ---------- Simpan jika ada perubahan ----------
      if (empty($changes)) {
        DB::rollBack();
        return response()->json(['message' => 'No changes detected.'], 200);
      }

      $order->save();

      // ---------- Clear cache ----------
      $this->clearOrderCache($order->public_id, $order->po_number);

      // ---------- Insert log ----------
      OrderLogActivity::create([
        'po_number' => $order->po_number,
        'vendor_name' => $order->vendors?->name,
        'order_data' => $order->toArray(),
        'order_blood_data' => $changes['blood_details']['new'] ?? null,
        'created_by_user_name' => $user->name,
        'status' => OrderLogActivityStatus::ORDER_UPDATED,
        'description' => generateOrderLogDescription(
          OrderLogActivityStatus::ORDER_UPDATED,
          $order->po_number,
          $user->id
        ),
        'timestamp' => now(),
      ]);

      DB::commit();

      globalLogger('info', 'Order data updated successfully!', [
        'id' => $order->id,
        'changes' => $changes,
      ], 200, 'updateorder');

      return response()->json([
        'message' => 'Order data updated successfully!',
        'data' => $order->fresh(['orderBloodDetails.bloodPacks', 'vendors']),
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      globalLogger('error', 'Order data failed to update!', [
        'id' => $id,
        'error' => $e->getMessage(),
      ], 500, 'updateorder');

      return response()->json(['message' => 'Failed to update order data!'], 500);
    }
  }
  // ---------- Fungsi untuk update data order :end ----------
}
