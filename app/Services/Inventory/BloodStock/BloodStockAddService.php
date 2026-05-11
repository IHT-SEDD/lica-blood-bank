<?php

namespace App\Services\Inventory\BloodStock;

use App\Enums\BloodStockLogActivityStatus;
use App\Enums\BloodStockStatus;
use App\Enums\IncomingBloodLogActivityStatus;
use App\Enums\IncomingBloodStatus;
use App\Models\BloodPack;
use App\Models\BloodStock;
use App\Models\BloodStockLogActivity;
use App\Models\IncomingBlood;
use App\Models\IncomingBloodDetail;
use App\Models\IncomingBloodLogActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BloodStockAddService
{
 // ---------- Fungsi untuk menambahkan data order baru via manual ----------
 public function insertBloodStockByManual(Request $request)
 {
  DB::beginTransaction();
  try {
   $user = Auth::user();
   $bagNumberItems = $request->input('bag_numbers', []);

   // ---------- Validasi bag_numbers tidak kosong ----------
   if (empty($bagNumberItems)) {
    return response()->json(['message' => 'Bag number list cannot be empty!'], 422);
   }

   // ---------- Validasi & klasifikasi tiap bag number ----------
   [$validDetails, $notFoundBags, $alreadyInStockBags] = $this->classifyBagNumbers($bagNumberItems);

   if (!empty($notFoundBags)) {
    return response()->json([
     'message' => 'Some bag numbers are not found or not ready!',
     'invalid_bags' => $notFoundBags,
    ], 422);
   }

   if (!empty($alreadyInStockBags)) {
    return response()->json([
     'message' => 'Some bag numbers are already in blood stock!',
     'duplicate_bags' => $alreadyInStockBags,
    ], 422);
   }

   // ---------- Insert BloodStock & update IncomingBloodDetail ----------
   $insertedStocks = $this->insertBloodStocks($validDetails, $request, $user);

   // ---------- Insert BloodStock log activity ----------
   $this->insertBloodStockLogs($insertedStocks, $request, $user);

   // ---------- Cek & update status IncomingBlood jika semua detail sudah ready ----------
   $this->syncIncomingBloodReadyStatus($validDetails, $request, $user);

   DB::commit();

   globalLogger('info', 'New blood stock added successfully!', [
    'po_number' => $request->po_number,
    'note' => $request->note,
    'total_inserted' => count($insertedStocks),
    'inserted_bags' => collect($insertedStocks)->pluck('stock.bag_number'),
    'inserted_by' => $user->id,
   ], 200, 'newbloodstock');
   return response()->json([
    'message' => 'New blood stock added successfully!',
    'total' => count($insertedStocks),
    'data' => collect($insertedStocks)->pluck('stock'),
   ]);
  } catch (\Throwable $e) {
   DB::rollBack();

   globalLogger('error', 'New blood stock failed to insert!', [
    'payload' => $request->all(),
    'error' => $e->getMessage(),
    'inserted_by' => Auth::id(),
   ], 500, 'newbloodstock');
   return response()->json([
    'message' => 'New blood stock failed to insert!',
    'error' => $e->getMessage(),
   ], 500);
  }
 }

 // ---------- Fungsi untuk menambahkan data blood stock baru via scan barcode ----------
 // Textarea dari frontend berisi bag numbers satu per baris (diinput oleh hardware
 // barcode reader). Fungsi ini mem-parse string tersebut menjadi array, lalu
 // menjalankan pipeline yang sama dengan insertBloodStockByManual.
 public function insertBloodStockByScan(Request $request)
 {
  DB::beginTransaction();
  try {
   $user = Auth::user();

   // ---------- Parse textarea: split by newline, trim, buang baris kosong ----------
   $rawInput = $request->input('bag_numbers', '');
   $bagNumberItems = collect(explode("\n", $rawInput))
    ->map(fn($line) => trim($line))
    ->filter(fn($line) => $line !== '')
    ->values()
    ->all();

   // ---------- Validasi bag_numbers tidak kosong ----------
   if (empty($bagNumberItems)) {
    return response()->json(['message' => 'Bag number list cannot be empty!'], 422);
   }

   // ---------- Validasi duplikat di sisi backend (safety net) ----------
   $duplicateBags = collect($bagNumberItems)
    ->duplicates()
    ->unique()
    ->values()
    ->all();

   if (!empty($duplicateBags)) {
    return response()->json([
     'message' => 'Bag number list contains duplicates!',
     'duplicate_bags' => $duplicateBags,
    ], 422);
   }

   // ---------- Validasi & klasifikasi tiap bag number ----------
   [$validDetails, $notFoundBags, $alreadyInStockBags] = $this->classifyBagNumbers($bagNumberItems);

   if (!empty($notFoundBags)) {
    return response()->json([
     'message' => 'Some bag numbers are not found or not ready!',
     'invalid_bags' => $notFoundBags,
    ], 422);
   }

   if (!empty($alreadyInStockBags)) {
    return response()->json([
     'message' => 'Some bag numbers are already in blood stock!',
     'duplicate_bags' => $alreadyInStockBags,
    ], 422);
   }

   // ---------- Insert BloodStock & update IncomingBloodDetail ----------
   $insertedStocks = $this->insertBloodStocks($validDetails, $request, $user);

   // ---------- Insert BloodStock log activity ----------
   $this->insertBloodStockLogs($insertedStocks, $request, $user);

   // ---------- Cek & update status IncomingBlood jika semua detail sudah ready ----------
   $this->syncIncomingBloodReadyStatus($validDetails, $request, $user);

   DB::commit();

   globalLogger('info', 'New blood stock added successfully via scan!', [
    'po_number' => $request->po_number,
    'note' => $request->note,
    'total_inserted' => count($insertedStocks),
    'inserted_bags' => collect($insertedStocks)->pluck('stock.bag_number'),
    'inserted_by' => $user->id,
   ], 200, 'newbloodstock');
   return response()->json([
    'message' => 'New blood stock added successfully!',
    'total' => count($insertedStocks),
    'data' => collect($insertedStocks)->pluck('stock'),
   ]);
  } catch (\Throwable $e) {
   DB::rollBack();

   globalLogger('error', 'New blood stock failed to insert via scan!', [
    'payload' => $request->all(),
    'error' => $e->getMessage(),
    'inserted_by' => Auth::id(),
   ], 500, 'newbloodstock');
   return response()->json([
    'message' => 'New blood stock failed to insert!',
    'error' => $e->getMessage(),
   ], 500);
  }
 }

 // ==========================================================================
 // PRIVATE HELPERS
 // ==========================================================================

 // ---------- Helper: klasifikasi bag number menjadi valid / not found / duplicate ----------
 private function classifyBagNumbers(array $bagNumbers): array
 {
  // ---------- Ambil data incoming_blood_details ----------
  $details = IncomingBloodDetail::withoutTrashed()
   ->with([
    'incomingBloods:id,public_id,po_number,order_blood_id,status,batch_number',
    'bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component'
   ])
   ->whereIn('bag_number', $bagNumbers)
   ->where('is_ready', false)
   ->get()
   ->keyBy('bag_number');

  // ---------- Ambil id incoming_blood_details ----------
  $detailIds = $details->pluck('id')->all();

  // ---------- Check tiap id incoming_blood_details apakah ada di blood_stocks atau tidak ----------
  $existingInStock = BloodStock::whereIn('incoming_blood_detail_id', $detailIds)
   ->whereNull('deleted_at')
   ->pluck('incoming_blood_detail_id')
   ->flip(); // flip agar O(1) lookup

  // ---------- Buat variabel array penampung klasifikasi ----------
  $validDetails = [];
  $notFoundBags = [];
  $alreadyInStockBags = [];

  foreach ($bagNumbers as $bagNumber) {
   $detail = $details->get($bagNumber);

   if (!$detail) {
    $notFoundBags[] = $bagNumber;
    continue;
   }

   if ($existingInStock->has($detail->id)) {
    $alreadyInStockBags[] = $bagNumber;
    continue;
   }

   $validDetails[] = $detail;
  }

  return [$validDetails, $notFoundBags, $alreadyInStockBags];
 }

 // ---------- Helper: insert BloodStock & update is_ready di IncomingBloodDetail ----------
 private function insertBloodStocks(array $validDetails, Request $request, ?User $user): array
 {
  $insertedStocks = [];
  $now = now();

  foreach ($validDetails as $detail) {
   // ---------- Generate bag_number_lica ----------
   $bagNumberLica = $this->generateBagNumberLica($detail->bloodPacks);

   // ---------- Insert ke BloodStock ----------
   $bloodStock = BloodStock::create([
    'bag_number' => $detail->bag_number,
    'bag_number_lica' => $bagNumberLica,
    'incoming_blood_detail_id' => $detail->id,
    'blood_pack_id' => $detail->blood_pack_id,
    'blood_volume' => $detail->blood_volume,
    'aftap_date' => $detail->aftap_date,
    'process_date' => $detail->process_date,
    'expiry_date' => $detail->expiry_date,
    'is_hiv' => $detail->is_hiv,
    'is_hbsag' => $detail->is_hbsag,
    'is_hcv' => $detail->is_hcv,
    'is_syphilis' => $detail->is_syphilis,
    'is_expired' => $detail->is_expired,
    'blood_status' => BloodStockStatus::AVAILABLE,
    'add_new_note' => $request->note,
   ]);

   // ---------- Update is_ready & ready_at di IncomingBloodDetail ----------
   $detail->update([
    'is_ready' => true,
    'ready_at' => $now,
   ]);

   $insertedStocks[] = ['stock' => $bloodStock, 'detail' => $detail];
  }

  return $insertedStocks;
 }

 // ---------- Helper: insert BloodStock log activity untuk semua stock yang berhasil diinsert ----------
 private function insertBloodStockLogs(array $insertedStocks, Request $request, ?User $user): void
 {
  $logs = [];
  $now = now();

  foreach ($insertedStocks as $item) {
   $logs[] = [
    'public_id' => (string) \Illuminate\Support\Str::uuid(),
    'blood_stock_public_id' => $item['stock']->public_id,
    'payload' => json_encode([
     'incoming_blood_detail_public_id' => $item['detail']->public_id,
     'po_number' => $request->po_number,
     'bag_number' => $item['detail']->bag_number,
     'bag_number_lica' => $item['stock']->bag_number_lica,
     'add_new_note' => $request->note,
     'method_add' => $request->method_add,
    ]),
    'status' => $request->method_add === 'scan' ? BloodStockLogActivityStatus::BLOOD_STOCK_CREATED_BY_SCAN : BloodStockLogActivityStatus::BLOOD_STOCK_CREATED_BY_MANUAL,
    'description' => generateBloodStockLogDescription($request->method_add === 'scan' ? BloodStockLogActivityStatus::BLOOD_STOCK_CREATED_BY_SCAN : BloodStockLogActivityStatus::BLOOD_STOCK_CREATED_BY_MANUAL, $item['detail']->bag_number, $user->id),
    'created_by_user_name' => $user->name,
    'timestamp' => $now,
    'created_at' => $now,
    'updated_at' => $now,
   ];
  }

  // ---------- Bulk insert log agar tidak N query ----------
  BloodStockLogActivity::insert($logs);
 }

 // ---------- Helper: cek apakah semua detail di IncomingBlood sudah ready, lalu update ----------
 private function syncIncomingBloodReadyStatus(array $validDetails, Request $request, ?User $user): void
 {
  // ---------- Ambil semua incoming_blood_id yang unik dari detail yang baru diinsert ----------
  $incomingBloodIds = collect($validDetails)
   ->pluck('incoming_blood_id')
   ->unique()
   ->all();

  // Ambil data incoming_bloods
  $incomingBloods = IncomingBlood::with(['incomingBloodDetails'])
   ->whereIn('id', $incomingBloodIds)
   ->get();

  $now = now();

  foreach ($incomingBloods as $incomingBlood) {
   // ---------- Cek apakah semuanya sudah ready atau belum dari incoming_blood_details ----------
   $allReady = $incomingBlood->incomingBloodDetails->every(
    fn($d) => (bool) $d->is_ready === true
   );
   if (!$allReady) continue;

   // ---------- Update IncomingBlood jika semua detail sudah ready ----------
   $incomingBlood->update([
    'received_by_user_id' => $user->id,
    'received_at' => $now,
    'stock_ready_at' => $now,
    'status' => IncomingBloodStatus::STOCK_READY,
   ]);

   // ---------- Insert IncomingBloodLogActivity ----------
   IncomingBloodLogActivity::create([
    'incoming_blood_public_id' => $incomingBlood->public_id,
    'po_number' => $incomingBlood->po_number,
    'batch_number' => $incomingBlood->batch_number,
    'incoming_data' => $incomingBlood->fresh()->toArray(),
    'blood_data' => $incomingBlood->incomingBloodDetails
     ->map(fn($d) => $d->toArray())
     ->toArray(),
    'status' => IncomingBloodLogActivityStatus::INCOMING_ALL_STOCK_READY,
    'created_by_user_name' => $user->name,
    'description' => generateIncomingLogDescription(
     IncomingBloodLogActivityStatus::INCOMING_ALL_STOCK_READY,
     $incomingBlood->po_number,
     $user->id
    ),
   ]);
  }
 }

 // ---------- Helper: generate bag_number_lica yang unik ----------
 /**
  * Format: BS{bloodGroup}{bloodComponent}{4angkaRandom}{P/N}{sequence 7 digit}
  * Contoh: BSAWHC1234P0000001
  * BS      = prefix tetap
  *- A       = blood group (A/B/AB/O)
  *- WH      = blood component code (2 huruf)
  *- C       = 4 karakter random alfanumerik
  *- 1234    = 4 digit random
  *- P       = rhesus (P = positif, N = negatif)
  *- 0000001 = sequence auto-increment 7 digit 
  */
 private function generateBagNumberLica(BloodPack $bloodPack): string
 {
  $bloodGroup = strtoupper($bloodPack->blood_group->value);
  $bloodComponent = strtoupper(substr($bloodPack->blood_component->value, 0, 2));
  $rhesus = $bloodPack->blood_rhesus === '+' ? 'P' : 'N';

  // ---------- Prefix tetap per kombinasi blood pack ----------
  $prefix = "BS{$bloodGroup}{$bloodComponent}";

  // ---------- Ambil sequence terakhir untuk prefix+rhesus ini agar tidak duplikat ----------
  // Lock row agar concurrent insert tidak menghasilkan sequence yang sama
  $last = DB::table('blood_stocks')
   ->where('bag_number_lica', 'like', "{$prefix}%{$rhesus}%")
   ->whereNull('deleted_at')
   ->lockForUpdate()
   ->orderByDesc('bag_number_lica')
   ->value('bag_number_lica');

  // ---------- Parse sequence dari bag_number_lica terakhir ----------
  if ($last) {
   $lastSequence = (int) substr($last, -7);
   $sequence = $lastSequence + 1;
  } else {
   $sequence = 1;
  }

  if ($sequence > 9999999) {
   throw new \RuntimeException("Bag number LICA sequence limit reached for prefix {$prefix}{$rhesus}");
  }

  // ---------- 4 karakter random alfanumerik (huruf besar + angka) ----------
  $random = strtoupper(\Illuminate\Support\Str::random(4));

  $sequencePadded = str_pad($sequence, 7, '0', STR_PAD_LEFT);

  return "{$prefix}{$random}{$rhesus}{$sequencePadded}";
 }
}
