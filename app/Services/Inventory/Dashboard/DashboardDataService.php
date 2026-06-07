<?php

namespace App\Services\Inventory\Dashboard;

use App\Enums\BloodStockStatus;
use App\Models\BloodStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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
        $bloodRhesus = $request->string('blood_rhesus')->toString() ?: null;
        $bloodGroup  = $request->string('blood_group')->toString() ?: null;

        $query = BloodStock::query()
            ->withoutTrashed()
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
            ])
            ->with([
                'bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
                'bloodTransfusionDetails:id,public_id,blood_stock_id,blood_transfusion_id',
                'bloodTransfusionDetails.bloodTransfusion:id,public_id,patient_id',
                'bloodTransfusionDetails.bloodTransfusion.patient:id,public_id,name',
            ])
            ->whereNotIn('blood_status', [
                BloodStockStatus::DESTROYED,
            ])
            ->when($bloodRhesus, fn($q) => $q->whereHas(
                'bloodPacks',
                fn($sub) =>
                $sub->where('blood_rhesus', $bloodRhesus)
            ))
            ->when($bloodGroup, fn($q) => $q->whereHas(
                'bloodPacks',
                fn($sub) =>
                $sub->where('blood_group', $bloodGroup)
            ));

        return DataTables::eloquent($query)
            ->filter(function ($query) use ($request) {
                if ($request->filled('search.value')) {
                    $search = $request->input('search.value');
                    $query->where(function ($q) use ($search) {
                        $q->where('bag_number', 'like', "%{$search}%")
                            ->orWhere('bag_number_lica', 'like', "%{$search}%")
                            ->orWhere('expiry_date', 'like', "%{$search}%");
                    });
                }
                if ($request->filled('status')) {
                    $query->where('blood_status', $request->status);
                }
            })
            ->addColumn('blood_group', fn($row) => $row->bloodPacks?->blood_group)
            ->addColumn('blood_rhesus', fn($row) => $row->bloodPacks?->blood_rhesus)
            ->addColumn('blood_component', fn($row) => $row->bloodPacks?->blood_component)
            ->addColumn(
                'patient_name',
                fn($row) =>
                $row->bloodTransfusionDetails
                    ->first()?->bloodTransfusion?->patient?->name
            )
            ->editColumn('expiry_date', fn($row) => $row->expiry_date)
            ->order(function ($query) use ($request) {
                $columns = [
                    0 => 'bag_number',
                    1 => 'bag_number_lica',
                    2 => 'blood_volume',
                    3 => 'expiry_date',
                    4 => 'blood_status',
                ];
                $order = $request->input('order.0.column');
                $dir   = $request->input('order.0.dir', 'asc');

                if (isset($columns[$order])) {
                    $query->orderBy($columns[$order], $dir);
                } else {
                    $query->orderBy('expiry_date', 'asc');
                }
            })
            ->toJson();
    }
}
