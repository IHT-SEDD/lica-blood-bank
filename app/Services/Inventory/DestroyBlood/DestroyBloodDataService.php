<?php

namespace App\Services\Inventory\DestroyBlood;

use App\Enums\BloodStockStatus;
use App\Models\BloodDestroy;
use App\Models\BloodStock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class DestroyBloodDataService
{
    // ---------- Fungsi untuk menampilkan data ke tabel ----------
    public function bloodDestroyTable(Request $request)
    {
        $query = BloodDestroy::withTrashed()
            ->with([
                'bloodStocks:id,public_id,bag_number,blood_pack_id,blood_volume,expiry_date,blood_status,deleted_at',
                'bloodStocks.bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component'
            ]);

        $this->applyDateFilter($query, $request);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('blood_packs.blood_group', 'like', "%{$search}%")
                    ->orWhere('blood_packs.blood_component', 'like', "%{$search}%")
                    ->orWhere('blood_packs.blood_rhesus', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort_by')) {
            $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
        }

        return $query->paginate($request->filled('per_page', 10));
    }

    // ---------- Fungsi select bag number pada form add ----------
    public function selectBagNumber(Request $request): array
    {
        $search = $request->filled('q', '');

        // ---------- Ambil data dari model ----------
        $query = BloodStock::withTrashed()
            ->select(['id', 'public_id', 'bag_number'])
            ->whereNot('blood_status', BloodStockStatus::DESTROYED);

        // ---------- Handle search field ----------
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('bag_number', 'like', "%{$search}%");
            });
        }

        $data = $query
            ->limit(100)
            ->get();

        return [
            'results' => $data->map(function ($item) {
                return [
                    'id' => $item->public_id ?? $item->id,
                    'text' => $item->bag_number,
                ];
            })->values(),
        ];
    }

    // ---------- Fungsi mengambil data by id ----------
    public function getDataDestroyBloodById(string $id)
    {
        // ---------- Ambil data dari model ----------
        $bloodDestroy = BloodDestroy::withTrashed()
            ->with([
                'bloodStocks:id,public_id,bag_number,blood_status',
                'bloodStocks.bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
            ])
            ->where('public_id', $id)->first();
        if (!$bloodDestroy) {
            return response()->json(['message' => 'Blood not found!'], 422);
        }

        return $bloodDestroy;
    }

    // ---------- Helper: untuk menerima dan menerapkan filter tanggal pada data ----------
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
}
