<?php

namespace App\Http\Controllers;

use App\Models\BloodPack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BloodTransfusionController extends Controller
{
    // ---------- Halaman index ----------
    public function index()
    {
        return view('pages.blood-transfusion.index');
    }

    // ---------- Datatable Blood Pack ----------
    public function datatableBloodPack(Request $request)
    {
        $searchValue = trim($request->input('search.value', ''));
        $start = max((int) $request->input('start', 0), 0);
        $length = (int) $request->input('length', 10);
        $draw = (int) $request->input('draw', 1);

        $allItemsCacheKey = sprintf('blood-transfusion.blood-pack.all.%s', md5($searchValue ?: 'all'));

        $filteredItems = Cache::remember($allItemsCacheKey, 60, function () use ($searchValue) {
            $query = BloodPack::select(
                'blood_packs.id',
                'blood_packs.public_id',
                'blood_packs.blood_group',
                'blood_packs.blood_rhesus',
                'blood_packs.blood_component'
            )
                ->where('blood_packs.is_active', 1)
                ->whereNull('blood_packs.deleted_at');

            if ($searchValue !== '') {
                $query->where(function ($sub) use ($searchValue) {
                    $sub->where('blood_packs.blood_group', 'like', "%{$searchValue}%")
                        ->orWhere('blood_packs.blood_rhesus', 'like', "%{$searchValue}%")
                        ->orWhere('blood_packs.blood_component', 'like', "%{$searchValue}%");
                });
            }

            return $query->orderBy('blood_packs.blood_group')
                ->orderBy('blood_packs.blood_rhesus')
                ->orderBy('blood_packs.blood_component')
                ->get();
        });

        $recordsTotal = Cache::remember('blood-transfusion.blood-pack.records-total', 60, function () {
            return BloodPack::where('is_active', 1)
                ->whereNull('deleted_at')
                ->count();
        });

        $recordsFiltered = $filteredItems->count();
        if ($length === -1) {
            $pageData = $filteredItems->slice($start)->values();
        } else {
            $pageData = $filteredItems->slice($start, max($length, 1))->values();
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $pageData,
        ]);
    }
}
