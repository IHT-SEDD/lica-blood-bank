<?php

namespace App\Services\BloodTransfusion;

use App\Enums\BloodStockStatus;
use App\Models\BloodStock;
use App\Models\BloodTransfusionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BloodTransfusionWriteService
{
    // ---------- Fungsi Hold Blood Pack ----------
    public function holdBloodPack(string $detailPublicId): void
    {
        DB::beginTransaction();

        try {
            $detail = BloodTransfusionDetail::where('public_id', $detailPublicId)->lockForUpdate()->firstOrFail();

            if ($detail->blood_stock_id) {
                BloodStock::where('id', $detail->blood_stock_id)->lockForUpdate()->first()
                    ?->update(['blood_status' => BloodStockStatus::HOLD]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e; // lempar kembali agar controller bisa handle response-nya
        }
    }
}
