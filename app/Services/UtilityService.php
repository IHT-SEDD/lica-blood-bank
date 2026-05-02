<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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

    $modelClass = $modules['model'];
    $with = $this->normalizeWith($modules['with'] ?? []);
    $labelField = $modules['label'];

    // ---------- Ambil data dari model ----------
    $query = $modelClass::query()->with($with);

    // ---------- Handle search field ----------
    if ($search = $request->get('q')) {
      $this->applySearch($query, $labelField, $search);
    }

    // ---------- Limit data yang ditampilkan ----------
    $data = $query->limit(100)->get();

    // ---------- Lempar response ----------
    return [
      'results' => $data->map(function ($item) use ($modules, $labelField) {
        $text = isset($modules['label_callback'])
          ? call_user_func($modules['label_callback'], $item)
          : data_get($item, $labelField);

        return [
          'id' => $item->public_id ?? $item->id,
          'text' => $text,
        ];
      }),
    ];
  }
  // ---------- Fungsi mengambil data untuk dropdown select :end ----------

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
    $search = strtolower($request->get('q', ''));

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
      case 'purchase-order':
        $data = \App\Models\OrderBlood::query()
          ->select('public_id', 'po_number')
          ->get()
          ->map(fn($item) => [
            'id' => $item->public_id,
            'text' => $item->po_number,
          ]);
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
  // ---------- Helper: mengambil data statis :begin ----------

  // ---------- Fungsi untuk mengambil data berdasarkan model & id :begin ----------
  public function getDataById(Request $request, $data, $id): array
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
      ->where(function ($query) use ($id) {
        $query->where('public_id', $id)
          ->orWhere('id', $id);
      })
      ->firstOrFail();

    return $item->toArray();
  }
}
