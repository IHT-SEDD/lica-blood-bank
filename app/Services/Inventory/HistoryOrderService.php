<?php

namespace App\Services\Inventory;

use App\Enums\OrderBloodStatus;
use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class HistoryOrderService
{
  // ---------- Fungsi untuk menampilkan data history order ke tabel :begin ----------
  public function historyOrderTable(Request $request)
  {
    // ---------- Ambil data ----------
    $query = OrderBlood::withTrashed()
      ->with([
        'vendors:id,public_id,name',
        'orderBloods:id,public_id,order_blood_id,blood_group',
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
      $query->whereHas('orderBloods', function ($q) use ($request) {
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
          ->orWhereHas('orderBloods', function ($q) use ($search) {
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
  public function insertNewOrder(Request $request)
  {
    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      // ---------- Hitung total quantity dari semua blood data ----------
      $totalQuantity = collect($request->blood_data)
        ->sum(fn($item) => (int) $item['blood_quantity']);

      // ---------- Validasi PO Number ----------
      $poNumberExists = OrderBlood::where('po_number', $request->po_number)->exists();
      $poNumber = (!empty($request->po_number) && !$poNumberExists)
        ? $request->po_number
        : $this->generatePoNumber();

      // ---------- Ambil id vendor berdasarkan public_id ----------
      $vendorId = Vendor::where('public_id', $request->vendor_id)->value('id');

      // ---------- Insert ke tabel order_bloods ----------
      $newOrderData = OrderBlood::create([
        'vendor_id' => $vendorId,
        'po_number' => $poNumber,
        'total_quantity' => $totalQuantity,
        'status' => OrderBloodStatus::ORDER_CREATED,
      ]);

      // ---------- Insert detail per blood data ----------
      foreach ($request->blood_data as $item) {
        OrderBloodDetail::create([
          'order_blood_id' => $newOrderData->id,
          'blood_group' => $item['blood_group'],
          'rhesus' => $item['blood_rhesus'],
          'blood_component' => $item['blood_component'],
          'blood_volume' => $item['blood_volume'],
          'quantity' => $item['blood_quantity'],
          'is_hiv' => $item['is_hiv'] ?? false,
          'is_hcv' => $item['is_hcv'] ?? false,
          'is_hbsag' => $item['is_hbsag'] ?? false,
          'is_syphilis' => $item['is_syphilis'] ?? false,
        ]);
      }
      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger(
        'info',
        'New order data inserted succesfully!',
        [
          'id' => $newOrderData->id,
          'payload' => $newOrderData,
        ],
        200,
        'neworderadd'
      );

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => 'New order data inserted succesfully!',
        'data' => $newOrderData
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger(
        'error',
        'New order data failed to insert!',
        [
          'payload' => $request->all(),
          'error' => $e->getMessage(),
        ],
        500,
        'neworderadd'
      );

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => 'New order data failed to insert!',
      ], 500);
    }
  }
  // ---------- Fungsi untuk menambahkan data order baru :end ----------

  // ---------- Fungsi untuk edit data berdasarkan jenis master :begin ----------
  public function editData(string $master, Request $request, $id)
  {
    // ---------- Ambil data config master.php ----------
    $modules = $this->getMasterConfig($master);
    $modelClass = $modules['model'];

    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      // ---------- Ambil model ----------
      $model = new $modelClass;

      // ---------- Ambil data master ----------
      $query = $modelClass::query();
      $record = $query
        ->where('id', $id)
        ->orWhere('public_id', $id)
        ->firstOrFail();

      // ---------- Ambil hanya field yang dikirim (partial update) ----------
      $data = array_filter(
        $request->only($model->getFillable()),
        fn($value) => !is_null($value) && $value !== ''
      );

      // Jangan update data password
      if (array_key_exists('password', $data)) {
        if (empty($data['password'])) {
          unset($data['password']);
        }
      }

      // ---------- Kondisi khusus untuk insert user ----------
      if ($model instanceof \App\Models\User) {
        // ---------- Isi email otomatis jika value email kosong dan value username tidak kosong ----------
        if (
          array_key_exists('email', $data) &&
          empty($data['email']) &&
          !empty($data['username'] ?? $record->username)
        ) {
          $data['email'] = strtolower($data['username'] ?? $record->username) . '@licabloodbank.com';
        }
      }

      // ---------- Bandingkan perubahan data ----------
      $isSame = true;
      foreach ($data as $key => $value) {
        $old = trim((string)$record->$key);
        $new = trim((string)$value);

        if ($old !== $new) {
          $isSame = false;
          break;
        }
      }

      // ---------- Jika tidak ada perubahan ----------
      if ($isSame) {
        return response()->json([
          'message' => "No data changes",
          'data' => $record
        ], 200);
      }

      // ---------- Hook before update data ----------
      if (method_exists($modelClass, 'beforeUpdate')) {
        $modelClass::beforeUpdate($data, $record);
      }

      // ---------- Update ----------
      $record->update($data);


      // ---------- Sync role ----------
      if ($record instanceof \App\Models\User && $request->filled('role')) {
        $roleName = \Spatie\Permission\Models\Role::findByName($request->role);
        $record->syncRoles($roleName->name);
      }

      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger(
        'info',
        "Data for master $master updated successfully!",
        [
          'master' => $master,
          'id' => $record->id,
          'payload' => $record,
        ],
        200,
        'masterupdate'
      );

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => "Data for master $master updated successfully!",
        'data' => $record
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger(
        'error',
        "Data for master $master failed to update!",
        [
          'master' => $master,
          'error' => $e->getMessage(),
        ],
        500,
        'masterupdate'
      );

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => "Data for master $master failed to update!",
      ], 500);
    }
  }
  // ---------- Fungsi untuk edit data berdasarkan jenis master :end ----------

  // ---------- Fungsi untuk delete data berdasarkan jenis master :begin ----------
  public function deleteData(string $master, $id)
  {
    // ---------- Ambil data config master.php ----------
    $modules = $this->getMasterConfig($master);
    $modelClass = $modules['model'];

    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      // ---------- Ambil data master ----------
      $record = $modelClass::query()
        ->where('id', $id)
        ->orWhere('public_id', $id)
        ->firstOrFail();

      // ---------- Delete ----------
      $record->delete();

      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger(
        'info',
        "Data for master $master deleted successfully!",
        [
          'master' => $master,
          'id' => $record->id,
        ],
        200,
        'masterdelete'
      );

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => "Data for master $master deleted successfully!",
        'data' => $record
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger(
        'error',
        "Data for master $master failed to delete!",
        [
          'master' => $master,
          'error' => $e->getMessage(),
        ],
        500,
        'masterdelete'
      );

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => "Data for master $master failed to update!",
      ], 500);
    }
  }
  // ---------- Fungsi untuk delete data berdasarkan jenis master :end ----------

  // ---------- Fungsi untuk restore data berdasarkan jenis master :begin ----------
  public function restoreData(string $master, $id)
  {
    // ---------- Ambil data config master.php ----------
    $modules = $this->getMasterConfig($master);
    $modelClass = $modules['model'];

    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      // ---------- Ambil data master ----------
      $record = $modelClass::onlyTrashed()
        ->where('id', $id)
        ->orWhere('public_id', $id)
        ->firstOrFail();

      // ---------- Restore ----------
      $record->restore();

      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger(
        'info',
        "Data for master $master restored successfully!",
        [
          'master' => $master,
          'id' => $record->id,
        ],
        200,
        'masterrestore'
      );

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => "Data for master $master restored successfully!",
        'data' => $record
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger(
        'error',
        "Data for master $master failed to restore!",
        [
          'master' => $master,
          'error' => $e->getMessage(),
        ],
        500,
        'masterrestore'
      );

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => "Data for master $master failed to restore!",
      ], 500);
    }
  }
  // ---------- Fungsi untuk restore data berdasarkan jenis master :end ----------

  // ---------- Helper: mengambil data config master.php :begin ----------
  private function getMasterConfig($master = null)
  {
    // ---------- Ambil data config master.php ----------
    $modules = config('master');
    // ---------- Lempar 404 jika jenis master tidak ada di config ----------
    abort_unless(isset($modules[$master]), 404);
    // ---------- Kembalikan data sesuai key $master ----------
    if ($master !== null) {
      abort_unless(isset($modules[$master]), 404);
      return $modules[$master];
    }
    // ---------- Kembalikan semua isi config ----------
    return $modules;
  }
  // ---------- Helper: mengambil data config master.php :end ----------

  // ---------- Helper: mengambil kolom apa saja yang boleh dicari dari fillable model :begin ----------
  private function getSearchableColumns($model)
  {
    return (new $model)->getFillable();
  }
  // ---------- Helper: mengambil kolom apa saja yang boleh dicari dari fillable model :end ----------

  // ---------- Helper: untuk menerima dan menerapkan filter tanggal pada data :begin ----------
  protected function applyDateFilter($query, Request $request)
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


  // ---------- Helper: menerima dan melakukan filter data user berdasarkan role :begin ----------
  protected function filterUser($query, Request $request)
  {
    if ($request->filled('role')) {
      $query->role($request->role);
    }
  }
  // ---------- Helper: menerima dan melakukan filter data user berdasarkan role :end ----------

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
}
