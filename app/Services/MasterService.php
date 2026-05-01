<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class MasterService
{
  // ---------- Fungsi untuk query data berdasarkan jenis master :begin ----------
  public function datatable(string $master, Request $request)
  {
    // ---------- Ambil data config master.php ----------
    $modules = $this->getMasterConfig($master);
    $modelClass = $modules['model'];

    // ---------- Ambil semua data jenis master ----------
    $excludeWithTrashed = ['role'];

    if (in_array($master, $excludeWithTrashed)) {
      $query = $modelClass::query();
    } else {
      $query = $modelClass::withTrashed();
    }
    if (!empty($modules['with'])) {
      $query->with($modules['with']);
    }

    // ---------- Terapkan filter untuk tanggal pada data ----------
    $this->applyDateFilter($query, $request);

    // ---------- Terapkan filter khusus pada data ----------
    $this->applyMasterFilter($query, $master, $request);

    // ---------- Handle search pada kolom data ----------
    if ($request->filled('search')) {
      $search = $request->search;
      $columns = $this->getSearchableColumns($modelClass);

      $query->where(function ($q) use ($search, $columns) {
        foreach ($columns as $column) {
          $q->orWhere($column, 'like', "%{$search}%");
        }
      });
    }

    // ---------- Urutkan data master ----------
    if ($request->filled('sort_by')) {
      $query->orderBy(
        $request->sort_by,
        $request->sort_dir ?? 'asc'
      );
    }

    // ---------- Tampilkan data ke tabel frontend ----------
    return $query->paginate($request->get('per_page', 10));
  }
  // ---------- Fungsi untuk query data berdasarkan jenis master :end ----------

  // ---------- Fungsi untuk submit data berdasarkan jenis master :begin ----------
  public function submitData(string $master, Request $request)
  {
    // ---------- Ambil data config master.php ----------
    $modules = $this->getMasterConfig($master);
    $modelClass = $modules['model'];

    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      // ---------- Ambil model & data fillable ----------
      $model = new $modelClass;
      $data = $request->only($model->getFillable());

      // ---------- Panggil hook sebelum insert jika ada ----------
      if (method_exists($modelClass, 'beforeCreate')) {
        $modelClass::beforeCreate($data);
      }

      // ---------- Kondisi khusus untuk insert role ----------
      if ($modelClass === \Spatie\Permission\Models\Role::class) {
        if (empty($data['guard_name'])) {
          $data['guard_name'] = 'web';
        }

        $created = $modelClass::create($data);

        // clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
      }

      // ---------- Kondisi khusus untuk insert user ----------
      elseif ($model instanceof \App\Models\User) {
        if (empty($data['email']) && !empty($data['username'])) {
          $data['email'] = strtolower($data['username']) . '@licabloodbank.com';
        }

        $created = $modelClass::create($data);

        if ($request->filled('role')) {
          $roleName = \Spatie\Permission\Models\Role::findById($request->role);
          $created->syncRoles($roleName->name);
        }
      }

      // ---------- Default insert ----------
      else {
        $created = $modelClass::create($data);
      }
      DB::commit();
      // ---------- Mulai transaksi database :end ----------

      // ---------- Masukkan ke log untuk success ----------
      globalLogger(
        'info',
        `New data for master $master inserted successfully!`,
        [
          'master' => $master,
          'id' => $created->id,
          'payload' => $created,
        ],
        200,
        'masteradd'
      );

      // ---------- Lempar sukses respon ke frontend ----------
      return response()->json([
        'message' => `New data for master $master inserted successfully!`,
        'data' => $created
      ]);
    } catch (\Throwable $e) {
      // ---------- Batalkan transaksi database jika ada error ----------
      DB::rollBack();

      // ---------- Masukkan ke log untuk error ----------
      globalLogger(
        'error',
        `New data for master $master failed to insert!`,
        [
          'master' => $master,
          'error' => $e->getMessage(),
        ],
        500,
        'masteradd'
      );

      // ---------- Lempar error respon ke frontend ----------
      return response()->json([
        'message' => `New data for master $master failed to insert!`,
      ], 500);
    }
  }
  // ---------- Fungsi untuk submit data berdasarkan jenis master :end ----------

  // ---------- Fungsi untuk edit data berdasarkan jenis master :begin ----------
  public function editData(string $master, Request $request, string $id)
  {
    // ---------- Ambil data config master.php ----------
    $modules = $this->getMasterConfig($master);
    $modelClass = $modules['model'];

    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      // ---------- Ambil model ----------
      $model = new $modelClass;

      // ---------- Konfigurasi khusus ----------
      $useOnlyId = ['role'];

      // ---------- Ambil data master ----------
      $query = $modelClass::query();

      $query->where(function ($q) use ($id, $master, $useOnlyId) {
        if (in_array($master, $useOnlyId)) {
          $q->where('id', $id);
        } else {
          $q->where('id', $id)
            ->orWhere('public_id', $id);
        }
      });

      $record = $query->firstOrFail();

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

      // ---------- Handle role spatie ----------
      if ($record instanceof \Spatie\Permission\Models\Role) {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
      }

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
  public function deleteData(string $master, string $id)
  {
    // ---------- Ambil data config master.php ----------
    $modules = $this->getMasterConfig($master);
    $modelClass = $modules['model'];

    // ---------- Mulai transaksi database :begin----------
    DB::beginTransaction();
    try {
      // ---------- Konfigurasi khusus ----------
      $useOnlyId = ['role'];

      // ---------- Ambil data master ----------
      $query = $modelClass::query();

      $query->where(function ($q) use ($id, $master, $useOnlyId) {
        if (in_array($master, $useOnlyId)) {
          $q->where('id', $id);
        } else {
          $q->where('id', $id)
            ->orWhere('public_id', $id);
        }
      });

      $record = $query->firstOrFail();

      // ---------- Detach permission role ----------
      if ($record instanceof \Spatie\Permission\Models\Role) {
        $record->syncPermissions([]); // detach permissions
      }

      // ---------- Delete ----------
      $record->delete();

      // ---------- Clear cache role ----------
      if ($record instanceof \Spatie\Permission\Models\Role) {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
      }

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
  public function restoreData(string $master, string $id)
  {
    // ---------- Ambil data config master.php ----------
    $modules = $this->getMasterConfig($master);
    $modelClass = $modules['model'];

    if ($modelClass === \Spatie\Permission\Models\Role::class) {
      return response()->json([
        'message' => 'Role cannot be restored (no soft delete support)'
      ], 400);
    }

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
  private function getSearchableColumns(string $model)
  {
    return (new $model)->getFillable();
  }
  // ---------- Helper: mengambil kolom apa saja yang boleh dicari dari fillable model :end ----------

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

  // ---------- Helper: untuk menerima dan menerapkan filter khusus pada data :begin ----------
  protected function applyMasterFilter(Builder $query, string $master, Request $request)
  {
    switch ($master) {
      case 'user':
        $this->filterUser($query, $request);
        break;
    }
  }
  // ---------- Helper: untuk menerima dan menerapkan filter khusus pada data :end ----------

  // ---------- Helper: menerima dan melakukan filter data user berdasarkan role :begin ----------
  protected function filterUser(Builder $query, Request $request)
  {
    if ($request->filled('role')) {
      $query->role($request->role);
    }
  }
  // ---------- Helper: menerima dan melakukan filter data user berdasarkan role :end ----------

  // ---------- Fungsi untuk query data berdasarkan jenis master :begin ----------
  public function getDataById(string $master, string $id)
  {
    // ---------- Ambil data config master.php ----------
    $modules = $this->getMasterConfig($master);
    $modelClass = $modules['model'];

    // ---------- Konfigurasi khusus ----------
    $excludeWithTrashed = ['role'];
    $useOnlyId = ['role'];

    // ---------- Ambil data master ----------
    if (in_array($master, $excludeWithTrashed)) {
      $query = $modelClass::query();
    } else {
      $query = $modelClass::withTrashed();
    }

    if (!empty($modules['with'])) {
      $query->with($modules['with']);
    }

    $query->where(function ($q) use ($id, $master, $useOnlyId) {
      if (in_array($master, $useOnlyId)) {
        $q->where('id', $id);
      } else {
        $q->where('id', $id)
          ->orWhere('public_id', $id);
      }
    });

    $dataMaster = $query->first();

    if (!$dataMaster) {
      return response()->json(['message' => 'Data not found'], 404);
    }

    // ---------- Lempar data ke frontend ----------
    return $dataMaster;
  }
  // ---------- Fungsi untuk query data berdasarkan jenis master :end ----------
}
