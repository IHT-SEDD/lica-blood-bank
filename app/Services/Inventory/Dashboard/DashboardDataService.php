<?php

namespace App\Services\Inventory\Dashboard;

use App\Enums\BloodStockStatus;
use App\Models\BloodStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardDataService
{
    // ---------- Fungsi mengambil data untuk blood stat :begin ----------
    public function bloodStatData(): array
    {
        $raw = BloodStock::query()
            ->withoutTrashed()
            ->join('blood_packs', 'blood_stocks.blood_pack_id', '=', 'blood_packs.id')
            ->selectRaw("
                SUM(CASE WHEN blood_packs.blood_group = 'A'  AND blood_packs.blood_rhesus = '+' THEN 1 ELSE 0 END) as a_positive_count,
                SUM(CASE WHEN blood_packs.blood_group = 'B'  AND blood_packs.blood_rhesus = '+' THEN 1 ELSE 0 END) as b_positive_count,
                SUM(CASE WHEN blood_packs.blood_group = 'AB' AND blood_packs.blood_rhesus = '+' THEN 1 ELSE 0 END) as ab_positive_count,
                SUM(CASE WHEN blood_packs.blood_group = 'O'  AND blood_packs.blood_rhesus = '+' THEN 1 ELSE 0 END) as o_positive_count,
                SUM(CASE WHEN blood_packs.blood_group = 'A'  AND blood_packs.blood_rhesus = '-' THEN 1 ELSE 0 END) as a_negative_count,
                SUM(CASE WHEN blood_packs.blood_group = 'B'  AND blood_packs.blood_rhesus = '-' THEN 1 ELSE 0 END) as b_negative_count,
                SUM(CASE WHEN blood_packs.blood_group = 'AB' AND blood_packs.blood_rhesus = '-' THEN 1 ELSE 0 END) as ab_negative_count,
                SUM(CASE WHEN blood_packs.blood_group = 'O'  AND blood_packs.blood_rhesus = '-' THEN 1 ELSE 0 END) as o_negative_count
            ")
            ->whereNotIn('blood_status', [BloodStockStatus::TAKEN_OUT, BloodStockStatus::DESTROYED])
            ->first();

        $aPositive = (int) ($raw->a_positive_count  ?? 0);
        $bPositive = (int) ($raw->b_positive_count  ?? 0);
        $abPositive = (int) ($raw->ab_positive_count ?? 0);
        $oPositive = (int) ($raw->o_positive_count  ?? 0);
        $aNegative = (int) ($raw->a_negative_count  ?? 0);
        $bNegative = (int) ($raw->b_negative_count  ?? 0);
        $abNegative = (int) ($raw->ab_negative_count ?? 0);
        $oNegative = (int) ($raw->o_negative_count  ?? 0);

        return [
            // ---------- Data per rhesus ----------
            'a_positive'  => $aPositive,
            'b_positive'  => $bPositive,
            'ab_positive' => $abPositive,
            'o_positive'  => $oPositive,
            'a_negative'  => $aNegative,
            'b_negative'  => $bNegative,
            'ab_negative' => $abNegative,
            'o_negative'  => $oNegative,

            // ---------- Total per blood group (positif + negatif) ----------
            'blood_a_count'  => $aPositive  + $aNegative,
            'blood_b_count'  => $bPositive  + $bNegative,
            'blood_ab_count' => $abPositive + $abNegative,
            'blood_o_count'  => $oPositive  + $oNegative,
        ];
    }
    // ---------- Fungsi mengambil data untuk blood stat :end ----------

    //----------- Funsgi Untuk mengambil data blood stock :begin----------
    public function bloodStockTable(Request $request)
    {
        $bloodRhesus = $request->input('blood_rhesus');
        $bloodGroup = $request->input('blood_group');

        $query = BloodStock::withoutTrashed()
            ->select([
                'id',
                'public_id',
                'bag_number',
                'bag_number_lica',
                'blood_pack_id',
                'blood_volume',
                'expiry_date',
                'blood_status',
                'created_at',
                'updated_at',
            ])->with([
                'bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component'
            ])->whereNotIn('blood_status', [BloodStockStatus::TAKEN_OUT, BloodStockStatus::DESTROYED]);

        $query->whereHas('bloodPacks', function ($q) use ($bloodRhesus) {
            $q->where('blood_rhesus', $bloodRhesus);
        });
        $query->whereHas('bloodPacks', function ($q) use ($bloodGroup) {
            $q->where('blood_group', $bloodGroup);
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bag_number', 'like', "%{$search}%");
                $q->orWhere('bag_number_lica', 'like', "%{$search}%");
                $q->orWhere('expiry_date', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort_by')) {
            $query->orderBy(
                $request->sort_by,
                $request->sort_dir ?? 'asc'
            );
        } else {
            $query->orderBy('expiry_date', 'asc');
        }

        return $query->paginate($request->filled('per_page', 50));
    }
}
