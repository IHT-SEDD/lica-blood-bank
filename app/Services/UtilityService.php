<?php

namespace App\Services;

use App\Models\BloodTransfusion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UtilityService
{
  // ---------- Fungsi mengambil data untuk dropdown select ----------
  public function getSelectData(Request $request, string $select): array
  {
    $select = Str::kebab($select);
    $search = $request->filled('q') ? $request->q : '';

    // ---------- Ambil data config utility.php ----------
    $modules = $this->getUtilityConfig($select);

    // ---------- Handle enum / static data ----------
    if (isset($modules['type']) && $modules['type'] === 'enum') {
      return $this->getStaticSelectData($request, $select);
    }

    // ---------- Lempar error jika data yang dibutuhkan kosong ----------
    if (!$modules || empty($modules['model']) || empty($modules['label'])) {
      abort(404, "Invalid select configuration [$select]");
    }

    $modelClass = $modules['model'];
    $with = $this->normalizeWith($modules['with'] ?? []);
    $labelField = $modules['label'];

    $query = $modelClass::query()->with($with);

    if (Schema::hasColumn((new $modelClass)->getTable(), 'is_active')) {
      $query->where('is_active', true);
    }
    if (!empty($modules['conditions'])) {
      $this->applyConditions($query, $modules['conditions']);
    }
    if (!empty($search)) {
      $this->applySearch($query, $labelField, $search);
    }

    $data = $query->get();
    return [
      'results' => $data->map(function ($item) use ($modules, $labelField) {
        $text = isset($modules['label_callback'])
          ? call_user_func($modules['label_callback'], $item)
          : $this->resolveLabel($item, $modules);

        return [
          'id' => $item->public_id ?? $item->id,
          'text' => $text,
        ];
      })->values(),
    ];
  }

  // ---------- Fungsi mengambil data untuk dropdown select dengan case special ----------
  public function getSelectDataSpecial(Request $request, string $select, string $id): array
  {
    $select = Str::kebab($select);
    $search = $request->filled('q') ? $request->q : '';

    // ---------- Ambil data config utility.php ----------
    $modules = $this->getUtilityConfig($select);

    // ---------- Handle enum / static data ----------
    if (isset($modules['type']) && $modules['type'] === 'enum') {
      return $this->getStaticSelectData($request, $select);
    }

    // ---------- Lempar error jika data yang dibutuhkan kosong ----------
    if (!$modules || empty($modules['model']) || empty($modules['label'])) {
      abort(404, "Invalid select configuration [$select]");
    }

    $modelClass = $modules['model'];
    $with = $this->normalizeWith($modules['with'] ?? []);
    $labelField = $modules['label'];

    $query = $modelClass::query()->with($with);

    if (Schema::hasColumn((new $modelClass)->getTable(), 'is_active')) {
      $query->where('is_active', true);
    }

    if (!empty($modules['conditions'])) {
      $this->applyConditionsSpecial($query, $modules['conditions'], $id);
    }

    if (!empty($search)) {
      $this->applySearch($query, $labelField, $search);
    }

    $data = $query->orderBy('id', 'asc')->get();
    return [
      'results' => $data->map(function ($item) use ($modules, $labelField) {
        $text = isset($modules['label_callback'])
          ? call_user_func($modules['label_callback'], $item)
          : $this->resolveLabel($item, $modules);

        return [
          'id'   => $item->public_id ?? $item->id,
          'text' => $text,
        ];
      })->values(),
    ];
  }

  // ---------- Fungsi untuk mengambil data berdasarkan model & id ----------
  public function getDataById(Request $request, string $data, string $id): array
  {
    $key = Str::kebab($data);

    // ---------- Ambil config utility ----------
    $modules = $this->getUtilityConfig($key);

    // ---------- Lempar error jika config tidak valid ----------
    if (!$modules || empty($modules['model'])) {
      abort(404, "Invalid data configuration [$key]");
    }

    $modelClass = $modules['model'];
    $with = $this->normalizeWith($modules['with'] ?? []);

    // ---------- Cari data berdasarkan public_id atau id ----------
    $item = $modelClass::with($with)
      ->when(Str::isUuid($id), function ($query) use ($id) {
        $query->where('public_id', $id);
      }, function ($query) use ($id) {
        $query->where('id', $id);
      })
      ->when(
        Schema::hasColumn((new $modelClass)->getTable(), 'is_active'),
        fn($query) => $query->where('is_active', true)
      );

    if (!empty($modules['conditions'])) {
      $this->applyConditions($item, $modules['conditions']);
    }

    $item = $item->first();

    return $item->toArray();
  }

  // ---------- Fungsi untuk mengambil data nomor bdrs ----------
  public function getSelectBdrsNumber(Request $request): array
  {
    $search = $request->filled('q') ? $request->q : '';

    $query = BloodTransfusion::withoutTrashed()
      ->whereNotNull('lab_number')
      ->whereNull('canceled_at')
      ->select('id', 'lab_number');

    if (!empty($search)) {
      $query->where('lab_number', 'like', "%{$search}%");
    }

    $data = $query
      ->orderBy('lab_number')
      ->get();

    return [
      'results' => $data->map(fn($item) => [
        'id' => $item->id,
        'text' => $item->lab_number,
      ])->values(),
    ];
  }

  // ---------- Fungsi untuk mengambil data nomor order ----------
  public function getSelectOrderNumber(Request $request): array
  {
    $search = $request->filled('q') ? $request->q : '';

    $query = BloodTransfusion::withoutTrashed()
      ->whereNotNull('order_number')
      ->whereNull('canceled_at')
      ->select('id', 'order_number');

    if (!empty($search)) {
      $query->where('order_number', 'like', "%{$search}%");
    }

    $data = $query
      ->orderBy('order_number')
      ->get();

    return [
      'results' => $data->map(fn($item) => [
        'id' => $item->id,
        'text' => $item->order_number,
      ])->values(),
    ];
  }

  // ---------- HELPERS ----------
  private function normalizeWith(array $with): array
  {
    if (empty($with)) return [];

    return is_array($with) ? $with : [$with];
  }
  private function applySearch(Builder $query, string $field, string $search): void
  {
    if (Str::contains($field, '.')) {
      [$relation, $column] = explode('.', $field);

      $query->whereHas($relation, function ($q) use ($column, $search) {
        $q->where($column, 'like', "%{$search}%");
      });
    } else {
      $query->where($field, 'like', "%{$search}%");
    }
  }
  private function getUtilityConfig($utility = null)
  {
    // ---------- Ambil data config utility.php ----------
    $modules = config('utility');
    // ---------- Lempar 404 jika jenis utility tidak ada di config ----------
    abort_unless(isset($modules[$utility]), 404);
    // ---------- Kembalikan data sesuai key $utility ----------
    if ($utility !== null) {
      abort_unless(isset($modules[$utility]), 404);
      return $modules[$utility];
    }
    // ---------- Kembalikan semua isi config ----------
    return $modules;
  }
  private function getStaticSelectData(Request $request, string $select): array
  {
    $search = strtolower($request->filled('q', ''));

    switch ($select) {
      case 'blood-group':
        $data = collect(\App\Enums\BloodGroup::toSelect());
        break;
      case 'blood-component':
        $data = collect(\App\Enums\BloodComponent::toSelect());
        break;
      case 'order-status':
        $data = collect(\App\Enums\OrderBloodStatus::toSelect());
        break;
      case 'blood-status':
        $data = collect(\App\Enums\BloodPackStatus::toSelect());
        break;
      case 'blood-stock-status':
        $data = collect(\App\Enums\BloodStockStatus::toSelect());
        break;
      case 'blood-transfusion-status':
        $data = collect(\App\Enums\BloodTransfusionStatus::toSelect());
        break;
      case 'dct-value':
        $data = collect(\App\Enums\DCTValue::toSelect());
        break;
      case 'relation-type':
        $data = collect(\App\Enums\RelationType::toSelect());
        break;
      case 'rack-type':
        $data = collect(\App\Enums\StorageRackType::toSelect());
        break;
      case 'result-test':
        $data = collect(\App\Enums\ResultTest::toSelect());
        break;
      case 'incoming-stock-status':
        $data = collect(\App\Enums\IncomingBloodStatus::toSelect());
        break;
      case 'blood-rhesus':
        $data = collect(['+', '-'])->map(fn($item) => [
          'id' => $item,
          'text' => $item,
        ]);
        break;
      case 'add-incoming-stock-method':
        $data = collect(\App\Enums\AddIncomingStockMethod::toSelect());
        break;

      default:
        return ['results' => []];
    }

    // ---------- Optional: search ----------
    if ($search) {
      $data = $data->filter(
        fn($item) =>
        str_contains(strtolower($item['text']), $search)
      );
    }

    return [
      'results' => $data->values(),
    ];
  }
  private function applyConditions(Builder $query, array $conditions): void
  {
    foreach ($conditions as $cond) {
      $field = $cond['field'] ?? null;
      $operator = $cond['operator'] ?? '=';
      $value = $cond['value'] ?? null;

      if (!$field) continue;

      // ---------- Handle whereNotIn :begin ----------
      if ($operator === 'not_in') {
        $values = is_array($value) ? $value : [$value];
        $query->whereNotIn($field, $values);
        continue;
      }
      // ---------- Handle whereNotIn :end ----------

      $query->where($field, $operator, $value);
    }
  }
  private function applyConditionsSpecial(Builder $query, array $conditions, string $id): void
  {
    foreach ($conditions as $cond) {
      $operator = $cond['operator'] ?? '=';

      // ---------- Handle whereHas via relasi :begin ----------
      if ($operator === 'whereHas') {
        $relation   = $cond['relation'] ?? null;
        $valueField = $cond['value_field'] ?? 'public_id';

        if (!$relation) continue;

        $query->whereHas($relation, function ($q) use ($valueField, $id) {
          $q->where($valueField, $id);
        });

        continue;
      }
      // ---------- Handle whereHas via relasi :end ----------

      // ---------- Handle whereIn :begin ----------
      if ($operator === 'in') {
        $field  = $cond['field'] ?? null;
        if (!$field) continue;

        $values = is_array($id) ? $id : explode(',', $id);
        $query->whereIn($field, $values);
        continue;
      }
      // ---------- Handle whereIn :end ----------

      // ---------- Handle whereNotIn :begin ----------
      if ($operator === 'not_in') {
        $field = $cond['field'] ?? null;
        if (!$field) continue;

        $values = is_array($id) ? $id : explode(',', $id);
        $query->whereNotIn($field, $values);
        continue;
      }
      // ---------- Handle whereNotIn :end ----------

      // ---------- Handle whereNull :begin ----------
      if ($operator === 'whereNull') {
        $field = $cond['field'] ?? null;
        if (!$field) continue;

        $query->whereNull($field);
        continue;
      }
      // ---------- Handle whereNull :end ----------

      // ---------- Handle kondisi biasa :begin ----------
      $field = $cond['field'] ?? null;
      $value = $cond['value'] ?? $id;

      if (!$field) continue;

      $query->where($field, $operator, $value);
      // ---------- Handle kondisi biasa :end ----------
    }
  }
  private function resolveLabel($item, array $modules): string
  {
    $labelField = $modules['label'];
    $separator = $modules['label_separator'] ?? ' - ';

    $fields = is_array($labelField) ? $labelField : [$labelField];

    return collect($fields)
      ->map(fn($field) => $this->resolveField($item, $field))
      ->filter(fn($value) => $value !== null && $value !== '')
      ->implode($separator);
  }
  private function resolveField($item, string $field)
  {
    // ---------- Handle path relasi satu level, misal "storages.name" ----------
    if (Str::contains($field, '.')) {
      [$relation, $rest] = explode('.', $field, 2);
      $related = data_get($item, $relation);

      // ---------- Jika relasi hasMany/belongsToMany (Collection) ----------
      if ($related instanceof \Illuminate\Support\Collection) {
        return $related
          ->map(fn($rel) => data_get($rel, $rest))
          ->filter(fn($v) => $v !== null && $v !== '')
          ->implode(', ');
      }
    }

    return data_get($item, $field);
  }
}
