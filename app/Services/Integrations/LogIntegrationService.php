<?php

namespace App\Services\Integrations;

use App\Models\LogIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class LogIntegrationService
{
    // ---------- Insert data ----------
    public function insertData(string $type, string $status, string $message, array $payload, ?string $endpoint = null)
    {
        return DB::transaction(function () use ($type, $status, $message, $payload, $endpoint) {
            // Auto-extract order number
            $orderNumber = $payload['transaksi']['no_order'] ??
                $payload['order_number'] ??
                $payload['no_ref'] ??
                null;

            // Auto-resolve endpoint if null
            $resolvedEndpoint = $endpoint ?? request()->url();

            return LogIntegration::create([
                'order_number' => $orderNumber,
                'endpoint' => $resolvedEndpoint,
                'payload' => $payload, // Cast to array in model, so direct array is fine
                'message' => $message,
                'status' => $status,
                'type' => $type,
                'is_active' => true,
            ]);
        });
    }

    // ---------- Get Datatable Response ----------
    public function getDatatable(Request $request)
    {
        $type = $request->input('type', 'new_request');

        $query = LogIntegration::query()
            ->where('type', $type)
            ->withoutTrashed();

        // Apply date range filter
        $this->applyDateRangeFilter($query, $request->input('date_range'));

        return DataTables::eloquent($query)
            ->filter(function ($query) use ($request) {
                $search = trim($request->input('search.value', ''));
                if (!empty($search)) {
                    $query->where(function ($sub) use ($search) {
                        $sub->where('order_number', 'like', "%{$search}%")
                            ->orWhere('endpoint', 'like', "%{$search}%")
                            ->orWhere('message', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
                    });
                }
            })
            ->order(function ($query) use ($request) {
                $columns = [
                    0 => 'created_at',
                    1 => 'order_number',
                    2 => 'message',
                    3 => 'status',
                    4 => 'endpoint',
                ];
                $order = $request->input('order.0.column');
                $dir = $request->input('order.0.dir', 'desc');
                if (isset($columns[$order])) {
                    $query->orderBy($columns[$order], $dir);
                } else {
                    $query->orderBy('created_at', 'desc');
                }
            })
            ->addColumn('row_data', function ($item) {
                return [
                    'public_id' => $item->public_id,
                    'created_at' => $item->created_at ? $item->created_at->toDateTimeString() : '-',
                    'order_number' => $item->order_number ?? '-',
                    'message' => $item->message ?? '-',
                    'status' => $item->status ?? '-',
                    'endpoint' => $item->endpoint ?? '-',
                    'payload' => $item->payload,
                ];
            })
            ->toJson();
    }

    // ---------- Helper: Apply Date Range Filter ----------
    private function applyDateRangeFilter($query, ?string $dateRange): void
    {
        if (empty($dateRange)) {
            // Default to today if no date range is provided
            $query->whereDate('created_at', Carbon::now()->format('Y-m-d'));
            return;
        }

        try {
            $dates = explode(' to ', $dateRange);

            if (count($dates) === 2) {
                $start = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                $end   = Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            } elseif (count($dates) === 1) {
                $date = Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
                $query->whereDate('created_at', $date);
            }
        } catch (\Exception $e) {
            logger()->error('LogIntegration Date range filter error: ' . $e->getMessage());
        }
    }
}
