<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UtilityService
{
 // ---------- Fungsi mengambil data untuk dropdown select :begin ----------
 public function getSelectData(Request $request, string $select): array
 {
  // ---------- Ambil data config utility.php ----------
  $modules = $this->getUtilityConfig($select);
  $modelClass = $modules['model'];
  $with = $this->normalizeWith($modules['with'] ?? []);
  $labelField = $modules['label'];

  // ---------- Lempar error jika data yang dibutuhkan kosong ----------
  if (!$modules || empty($modules['model']) || empty($modules['label'])) {
   abort(404, "Invalid select configuration [$select]");
  }

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
   'results' => $data->map(function ($item) use ($labelField) {
    return [
     'id' => $item->public_id ?? $item->id,
     'text' => data_get($item, $labelField),
    ];
   }),
  ];
 }
 // ---------- Fungsi mengambil data untuk dropdown select :end ----------

 // ---------- Helper untuk memformat relasi model :begin ----------
 private function normalizeWith($with): array
 {
  if (empty($with)) return [];

  return is_array($with) ? $with : [$with];
 }
 // ---------- Helper untuk memformat relasi model :end ----------

 // ---------- Helper untuk menerapkan search data :begin ----------
 private function applySearch($query, string $field, string $search): void
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
}
