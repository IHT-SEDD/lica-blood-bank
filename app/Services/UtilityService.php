<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UtilityService
{
  // ---------- Fungsi mengambil data untuk dropdown select :begin ----------
  public function getSelectData(Request $request, string $select): array
  {
    $select = Str::kebab($select);

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

    $search = $request->filled('q', '');
    $modelClass = $modules['model'];
    $with = $this->normalizeWith($modules['with'] ?? []);
    $labelField = $modules['label'];

    // ---------- Ambil data dari model ----------
    $query = $modelClass::query()->with($with);

    if (Schema::hasColumn((new $modelClass)->getTable(), 'is_active')) {
      $query->where('is_active', true);
    }

    if (!empty($modules['conditions'])) {
      $this->applyConditions($query, $modules['conditions']);
    }

    // ---------- Handle search field ----------
    if (!empty($search)) {
      $this->applySearch($query, $labelField, $search);
    }

    $data = $query->limit(100)->get();

    return [
      'results' => $data->map(function ($item) use ($modules, $labelField) {
        $text = isset($modules['label_callback'])
          ? call_user_func($modules['label_callback'], $item)
          : data_get($item, $labelField);

        return [
          'id' => $item->public_id ?? $item->id,
          'text' => $text,
        ];
      })->values(),
    ];
  }
  // ---------- Fungsi mengambil data untuk dropdown select :end ----------

  // ---------- Fungsi mengambil data untuk dropdown select dengan case special :begin ----------
  public function getSelectDataSpecial(Request $request, string $select, string $id): array
  {
    $select = Str::kebab($select);

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

    $search = $request->filled('q') ? $request->q : '';
    $modelClass  = $modules['model'];
    $with = $this->normalizeWith($modules['with'] ?? []);
    $labelField = $modules['label'];

    // ---------- Ambil data dari model ----------
    $query = $modelClass::query()->with($with);

    if (Schema::hasColumn((new $modelClass)->getTable(), 'is_active')) {
      $query->where('is_active', true);
    }

    if (!empty($modules['conditions'])) {
      $this->applyConditionsSpecial($query, $modules['conditions'], $id);
    }

    // ---------- Handle search field ----------
    if (!empty($search)) {
      $this->applySearch($query, $labelField, $search);
    }

    $data = $query->limit(100)->get();

    return [
      'results' => $data->map(function ($item) use ($modules, $labelField) {
        $text = isset($modules['label_callback'])
          ? call_user_func($modules['label_callback'], $item)
          : data_get($item, $labelField);

        return [
          'id' => $item->public_id ?? $item->id,
          'text' => $text,
        ];
      })->values(),
    ];
  }
  // ---------- Fungsi mengambil data untuk dropdown select dengan case special :end ----------

  // ---------- Helper untuk memformat relasi model :begin ----------
  private function normalizeWith(array $with): array
  {
    if (empty($with)) return [];

    return is_array($with) ? $with : [$with];
  }
  // ---------- Helper untuk memformat relasi model :end ----------

  // ---------- Helper untuk menerapkan search data :begin ----------
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
  // ---------- Helper untuk menerapkan search data :end ----------

  // ---------- Helper: mengambil data config utility.php :begin ----------
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
  // ---------- Helper: mengambil data config utility.php :end ----------

  // ---------- Helper: mengambil data statis :begin ----------
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
      case 'relation-type':
        $data = collect(\App\Enums\RelationType::toSelect());
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
  // ---------- Helper: mengambil data statis :end ----------

  // ---------- Helper: mengambil data condition dari config :begin ----------
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
  // ---------- Helper: mengambil data condition dari config :end ----------

  // ---------- Helper: apply conditions untuk select-special (dengan $id dari URL) :begin ----------
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
  // ---------- Helper: apply conditions untuk select-special (dengan $id dari URL) :end ----------

  // ---------- Fungsi untuk mengambil data berdasarkan model & id :begin ----------
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
}
