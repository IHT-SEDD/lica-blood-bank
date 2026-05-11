<?php

namespace App\Services\Inventory;

use App\Models\IncomingBlood;
use App\Models\IncomingBloodDetail;
use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class StockInDetailService
{
 // ---------- Fungsi untuk menampilkan data incoming blood ke tabel incoming blood detail :begin ----------
 public function incomingStockTable(Request $request, string $id)
 {
  $incomingBloodId = IncomingBlood::withoutTrashed()->where('public_id', $id)->value('id');

  $query = IncomingBloodDetail::withTrashed()
   ->select([
    'id',
    'public_id',
    'incoming_blood_id',
    'bag_number',
    'blood_pack_id',
    'blood_volume',
    'aftap_date',
    'process_date',
    'expiry_date',
    'ready_at',
    'created_at',
   ])
   ->with([
    'bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component'
   ])
   ->where('incoming_blood_id', $incomingBloodId);

  if ($request->filled('search')) {
   $search = $request->search;
   $query->where(function ($q) use ($search) {
    $q->where('bag_number', 'like', "%{$search}%");
   });
  }

  if ($request->filled('sort_by')) {
   $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
  } else {
   $query->latest();
  }

  return $query->paginate($request->filled('per_page', 10));
 }
 // ---------- Fungsi untuk menampilkan data incoming blood ke tabel incoming blood detail :end ----------

 // ---------- Fungsi untuk menampilkan data order ke tabel order blood detail :begin ----------
 public function orderDataTable(Request $request, string $id)
 {
  $poNumber = IncomingBlood::withoutTrashed()->where('public_id', $id)->value('po_number');
  $orderBloodId = OrderBlood::withoutTrashed()->where('po_number', $poNumber)->value('id');

  $query = OrderBloodDetail::withoutTrashed()
   ->select([
    'id',
    'public_id',
    'order_blood_id',
    'blood_pack_id',
    'note',
    'quantity',
    'created_at',
   ])->with([
    'bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
    'orderBloods:id,public_id,ordered_by_user_id',
    'orderBloods.users:id,name',
   ])
   ->where('order_blood_id', $orderBloodId);

  $this->applyDateFilter($query, $request);

  if ($request->filled('search')) {
   $search = $request->search;
   $query->where(function ($q) use ($search) {
    $q->where('po_number', 'like', "%{$search}%");
   });
  }

  if ($request->filled('sort_by')) {
   $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
  } else {
   $query->latest();
  }

  return $query->paginate($request->filled('per_page', 10));
 }
 // ---------- Fungsi untuk menampilkan data order ke tabel order blood detail :end ----------

 // ---------- Fungsi untuk mengambil data order :begin ----------
 public function getDataIncoming(string $id)
 {
  $dataIncoming = IncomingBlood::withoutTrashed()->where('public_id', $id)->first();

  if (!$dataIncoming) {
   return response()->json(['message' => 'Data incoming not found'], 404);
  }

  return response()->json($dataIncoming);
 }
 // ---------- Fungsi untuk mengambil data order :end ----------

 // ---------- Fungsi untuk mengambil data order :begin ----------
 public function getDataOrder(string $id)
 {
  $poNumber = IncomingBlood::withoutTrashed()->where('public_id', $id)->value('po_number');
  $dataOrder = OrderBlood::withoutTrashed()->where('po_number', $poNumber)
  ->with([
    'vendors:id,public_id,name',
    'users:id,name',
   ])
  ->first();

  if (!$dataOrder) {
   return response()->json(['message' => 'Data order not found'], 404);
  }

  return response()->json($dataOrder);
 }
 // ---------- Fungsi untuk mengambil data order :end ----------

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
