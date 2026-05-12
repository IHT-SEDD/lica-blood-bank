<?php

namespace App\Services\Inventory\DestroyBlood;

use App\Enums\BloodDestroyStatus;
use App\Enums\BloodStockLogActivityStatus;
use App\Enums\BloodStockStatus;
use App\Models\BloodDestroy;
use App\Models\BloodStock;
use App\Models\BloodStockLogActivity;
use App\Models\StorageRackBlood;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DestroyBloodAddService
{
    // ---------- Fungsi untuk menambahkan data via manual ----------
    public function insertBloodDestroyByManual(Request $request)
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
            [$validDetails, $notFoundBags, $alreadyDestroyed] = $this->classifyBagNumbers($bagNumberItems);
            if (!empty($notFoundBags)) {
                return response()->json([
                    'message' => 'Some bag numbers are not found or not eligible to destroy!',
                    'invalid_bags' => $notFoundBags,
                ], 422);
            }
            if (!empty($alreadyDestroyed)) {
                return response()->json([
                    'message' => 'Some bag numbers are already destroyed!',
                    'duplicate_bags' => $alreadyDestroyed,
                ], 422);
            }

            // ---------- Insert BloodDestroy & update BloodStock ----------
            $destroyedBloods = $this->insertBloodDestroys($validDetails, $request, $user);

            // ---------- Insert BloodStock log activity ----------
            $this->insertBloodStockLogs($destroyedBloods, $user);

            DB::commit();

            globalLogger('info', 'Blood destroyed successfully!', [
                'reason' => $request->reason,
                'total_destroyed' => count($destroyedBloods),
                'destroyed_bags' => collect($destroyedBloods)->pluck('bag_number'),
                'destroyed_by' => $user->id,
            ], 200, 'newblooddestroy');
            globalLogger('info', 'Blood destroyed successfully!', [
                'reason' => $request->reason,
                'total_destroyed' => count($destroyedBloods),
                'destroyed_bags' => collect($destroyedBloods)->pluck('bag_number'),
                'destroyed_by' => $user->id,
            ], 200, 'destroybloodstock');
            return response()->json([
                'message' => 'Blood destroyed successfully!',
                'total' => count($destroyedBloods),
                'data' => collect($destroyedBloods)->pluck('stock'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            globalLogger('error', 'Blood failed to destroy!', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'inserted_by' => Auth::id(),
            ], 500, 'newblooddestroy');
            globalLogger('error', 'Blood failed to destroy!', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'inserted_by' => Auth::id(),
            ], 500, 'destroybloodstock');
            return response()->json([
                'message' => 'Blood failed to destroy!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ---------- Fungsi untuk menambahkan data via scan ----------
    public function insertBloodDestroyByScan(Request $request)
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
            [$validDetails, $notFoundBags, $alreadyDestroyed] = $this->classifyBagNumbers($bagNumberItems);
            if (!empty($notFoundBags)) {
                return response()->json([
                    'message' => 'Some bag numbers are not found or not eligible to destroy!',
                    'invalid_bags' => $notFoundBags,
                ], 422);
            }
            if (!empty($alreadyDestroyed)) {
                return response()->json([
                    'message' => 'Some bag numbers are already destroyed!',
                    'duplicate_bags' => $alreadyDestroyed,
                ], 422);
            }

            // ---------- Insert BloodDestroy & update BloodStock ----------
            $destroyedBloods = $this->insertBloodDestroys($validDetails, $request, $user);

            // ---------- Insert BloodStock log activity ----------
            $this->insertBloodStockLogs($destroyedBloods, $user);

            DB::commit();

            globalLogger('info', 'Blood destroyed successfully via scan!', [
                'reason' => $request->reason,
                'total_destroyed' => count($destroyedBloods),
                'destroyed_bags' => collect($destroyedBloods)->pluck('bag_number'),
                'destroyed_by' => $user->id,
            ], 200, 'newblooddestroy');
            globalLogger('info', 'Blood destroyed successfully via scan!', [
                'reason' => $request->reason,
                'total_destroyed' => count($destroyedBloods),
                'destroyed_bags' => collect($destroyedBloods)->pluck('bag_number'),
                'destroyed_by' => $user->id,
            ], 200, 'destroybloodstock');
            return response()->json([
                'message' => 'Blood destroyed successfully!',
                'total' => count($destroyedBloods),
                'data' => collect($destroyedBloods)->pluck('stock'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            globalLogger('error', 'Blood failed to destroy via scan!', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'inserted_by' => Auth::id(),
            ], 500, 'newblooddestroy');
            globalLogger('error', 'Blood failed to destroy via scan!', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
                'inserted_by' => Auth::id(),
            ], 500, 'destroybloodstock');
            return response()->json([
                'message' => 'Blood failed to destroy!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ==========================================================================
    // PRIVATE HELPERS
    // ==========================================================================
    private function classifyBagNumbers(array $bagNumbers): array
    {
        // ---------- Ambil data blood_stocks ----------
        $details = BloodStock::withTrashed()
            ->with(['bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component'])
            ->whereIn('bag_number', $bagNumbers)
            ->get()
            ->keyBy('bag_number');

        $validDetails = [];
        $notFoundBags = [];
        $alreadyDestroyed = [];

        foreach ($bagNumbers as $bagNumber) {
            $detail = $details->get($bagNumber);
            // ---------- Bag number tidak ditemukan sama sekali ----------
            if (!$detail) {
                $notFoundBags[] = $bagNumber;
                continue;
            }
            // ---------- Sudah berstatus destroyed ----------
            if ($detail->blood_status === BloodStockStatus::DESTROYED) {
                $alreadyDestroyed[] = $bagNumber;
                continue;
            }
            $validDetails[] = $detail;
        }

        return [$validDetails, $notFoundBags, $alreadyDestroyed];
    }

    // ---------- Helper: delete blood dari storage rack blood (least filled) ----------
    private function storageRackBloodDeleteData(BloodStock $bloodStock): ?int
    {
        $rackBlood = StorageRackBlood::where('blood_stock_id', $bloodStock->id)
            ->lockForUpdate()
            ->first();

        // ---------- Jika tidak ada, kembalikan null ----------
        if (!$rackBlood) {
            return null;
        }

        // ---------- Delete ----------
        $rackBlood->delete();

        return $rackBlood->id;
    }

    // ---------- Helper: Insert blood_destroys ----------
    private function insertBloodDestroys(array $validDetails, Request $request, ?User $user): array
    {
        $destroyedBloodList = [];

        foreach ($validDetails as $detail) {
            // ---------- Insert ke BloodDestroy ----------
            BloodDestroy::create([
                'blood_stock_id' => $detail->id,
                'reason' => $request->reason,
                'status' => BloodDestroyStatus::DESTROYED,
            ]);

            // ---------- Update blood_status BloodStock menjadi destroyed ----------
            $detail->update([
                'blood_status' => BloodStockStatus::DESTROYED,
            ]);

            // ---------- Hapus data di storage rack (jika ada) ----------
            $this->storageRackBloodDeleteData($detail);

            $destroyedBloodList[] = [
                'stock' => $detail,
                'bag_number' => $detail->bag_number,
            ];
        }

        return $destroyedBloodList;
    }

    // ---------- Helper: insert BloodStock log activity untuk semua stock yang berhasil diinsert ----------
    private function insertBloodStockLogs(array $destroyedBloods, ?User $user): void
    {
        $logs = [];
        $now = now();
        $method = BloodStockLogActivityStatus::BLOOD_STOCK_DESTROYED;

        foreach ($destroyedBloods as $item) {
            /** @var BloodStock $stock */
            $stock = $item['stock'];

            $logs[] = [
                'public_id' => (string) \Illuminate\Support\Str::uuid(),
                'blood_stock_public_id' => $stock->public_id,
                'payload' => json_encode([
                    'bag_number' => $stock->bag_number,
                    'blood_status' => BloodStockStatus::DESTROYED->value,
                ]),
                'status' => $method->value,
                'description' => generateBloodStockLogDescription($method, $stock->bag_number, $user->id),
                'created_by_user_name' => $user->name,
                'timestamp' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // ---------- Bulk insert log agar tidak N query ----------
        BloodStockLogActivity::insert($logs);
    }
}
