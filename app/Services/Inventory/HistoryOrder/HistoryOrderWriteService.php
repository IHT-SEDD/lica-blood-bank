<?php

namespace App\Services\Inventory\HistoryOrder;

use App\Enums\OrderBloodStatus;
use App\Enums\OrderLogActivityStatus;
use App\Models\BloodPack;
use App\Models\OrderBlood;
use App\Models\OrderBloodDetail;
use App\Models\OrderLogActivity;
use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HistoryOrderWriteService
{
    const CACHE_ORDER_BY_ID_KEY = "order_and_log_data_by_id";
    const CACHE_ORDER_BY_PO_KEY = "order_and_log_data_by_PO";

    // ---------- Fungsi untuk menambahkan data order baru ----------
    public function insertNewOrder(Request $request, ?string $draft)
    {
        // ---------- Mulai transaksi database :begin----------
        DB::beginTransaction();
        try {
            // ---------- Hitung total quantity dari semua blood data ----------
            $totalQuantity = collect($request->blood_data)
                ->sum(fn($item) => (int) $item['quantity']);

            // ---------- Validasi PO Number ----------
            $poNumberExists = OrderBlood::where('po_number', $request->po_number)->exists();
            $poNumber = (!empty($request->po_number) && !$poNumberExists) ? $request->po_number : $this->generatePoNumber();

            // ---------- Ambil data vendor ----------
            $vendor = Vendor::where('public_id', $request->vendor_id)->first();

            $vendorId = $vendor?->id;
            $vendorName = $vendor?->name;

            // ---------- Ambil data user ----------
            $user = Auth::user();

            // ---------- Ambil status order ----------
            $status = $draft === 'draft' ? OrderBloodStatus::DRAFT : OrderBloodStatus::ORDER_CREATED;

            // ---------- Insert ke tabel order_bloods ----------
            $newOrderData = OrderBlood::create([
                'vendor_id' => $vendorId,
                'po_number' => $poNumber,
                'total_quantity' => $totalQuantity,
                'description' => $request->description ?? NULL,
                'status' => $status,
                'ordered_by_user_id' => $user->id,
            ]);

            // ---------- Insert detail per blood data ----------
            $orderBloodDetails = [];

            foreach ($request->blood_data as $item) {
                $bloodPack = BloodPack::where('public_id', $item['blood_pack_id'])->firstOrFail();

                $detail = OrderBloodDetail::create([
                    'order_blood_id' => $newOrderData->id,
                    'blood_pack_id' => $bloodPack->id,
                    'note' => $item['note'],
                    'quantity' => $item['quantity'],
                ]);

                $orderBloodDetails[] = $detail;
            }

            // ---------- Ambil status order log ----------
            $statusLog = $draft === 'draft'
                ? OrderLogActivityStatus::DRAFT_CREATED
                : OrderLogActivityStatus::ORDER_CREATED;

            // ---------- Insert Order Log Activity ----------
            OrderLogActivity::create([
                'po_number' => $poNumber,
                'vendor_name' => $vendorName,
                'order_data' => $newOrderData->toArray(),
                'order_blood_data' => collect($orderBloodDetails)
                    ->map(fn($d) => $d->toArray())
                    ->toArray(),
                'created_by_user_name' => $user->name,
                'status' => $statusLog,
                'description' => generateOrderLogDescription(
                    $statusLog,
                    $poNumber,
                    $user->id
                ),
                'timestamp' => $newOrderData->created_at,
            ]);

            DB::commit();
            // ---------- Mulai transaksi database :end ----------

            // ---------- Masukkan ke log untuk success ----------
            globalLogger('info', 'New order data inserted succesfully!', [
                'id' => $newOrderData->id,
                'payload' => $newOrderData,
            ], 200, 'neworderadd');

            // ---------- Lempar sukses respon ke frontend ----------
            return response()->json([
                'message' => 'New order data inserted succesfully!',
                'data' => $newOrderData
            ]);
        } catch (\Throwable $e) {
            // ---------- Batalkan transaksi database jika ada error ----------
            DB::rollBack();

            // ---------- Masukkan ke log untuk error ----------
            globalLogger('error', 'New order data failed to insert!', [
                'payload' => $request->all(),
                'error' => $e->getMessage(),
            ], 500, 'neworderadd');

            // ---------- Lempar error respon ke frontend ----------
            return response()->json([
                'message' => 'New order data failed to insert!',
            ], 500);
        }
    }

    // ---------- Fungsi untuk membuat po number ----------
    public function generatePoNumber(): string
    {
        $year = now()->format('Y');
        $last = OrderBlood::where('po_number', 'like', "P{$year}OB%")
            ->lockForUpdate()
            ->orderByDesc('po_number')
            ->first();
        $nextNumber = $last
            ? ((int) substr($last->po_number, -6) + 1)
            : 1;
        $poNumber = 'P' . $year . 'OB' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        // ---------- Lempar data ke frontend ----------
        return $poNumber;
    }

    // ---------- Fungsi untuk update data order ----------
    public function updateDataOrder(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            $order = OrderBlood::where('public_id', $id)
                ->with(['orderBloodDetails', 'vendors'])
                ->firstOrFail();

            // ---------- Hanya status draft / order_created yang boleh diedit ----------
            $editableStatuses = [
                OrderBloodStatus::DRAFT->value,
                OrderBloodStatus::ORDER_CREATED->value,
            ];
            if (!in_array($order->status->value, $editableStatuses)) {
                return response()->json(['message' => 'Order cannot be edited in current status!'], 422);
            }

            $changes = []; // Catat field yang berubah untuk log

            // ---------- Update field order (hanya yang dikirim & berubah) ----------
            $vendor = null;
            if ($request->has('vendor_id')) {
                $vendor = Vendor::where('public_id', $request->vendor_id)->firstOrFail();
                if ($order->vendor_id !== $vendor->id) {
                    $changes['vendor'] = [
                        'old' => $order->vendors?->name,
                        'new' => $vendor->name,
                    ];
                    $order->vendor_id = $vendor->id;
                }
            }

            if ($request->has('description') && $order->description !== $request->description) {
                $changes['description'] = [
                    'old' => $order->description,
                    'new' => $request->description,
                ];
                $order->description = $request->description;
            }

            // ---------- Update blood details jika dikirim ----------
            if ($request->has('blood_data')) {
                // Hapus semua detail lama, insert ulang yang baru
                $oldDetails = $order->orderBloodDetails
                    ->map(function ($detail) {
                        return [
                            'blood_pack_id' => $detail->blood_pack_id,
                            'quantity' => $detail->quantity,
                            'note' => $detail->note,
                        ];
                    })
                    ->toArray();

                $order->orderBloodDetails()->delete();

                $newDetails = [];
                $totalQuantity = 0;

                foreach ($request->blood_data as $item) {
                    $bloodPack = BloodPack::where('id', $item['blood_pack_id'])->firstOrFail();

                    $detail = OrderBloodDetail::create([
                        'order_blood_id' => $order->id,
                        'blood_pack_id' => $bloodPack->id,
                        'note' => $item['note'] ?? null,
                        'quantity' => $item['quantity'],
                    ]);

                    $newDetails[] = [
                        'blood_pack_id' => $bloodPack->public_id,
                        'blood_pack_name' => sprintf(
                            '%s%s %s',
                            $bloodPack->blood_group?->value,
                            $bloodPack->blood_rhesus,
                            $bloodPack->blood_component?->value,
                        ),
                        'quantity' => $detail->quantity,
                        'note' => $detail->note,
                    ];

                    $totalQuantity += (int) $item['quantity'];
                }

                if ($oldDetails !== $newDetails) {
                    $changes['blood_details'] = [
                        'old' => $oldDetails,
                        'new' => $newDetails,
                    ];
                }

                $order->total_quantity = $totalQuantity;
            }

            // ---------- Simpan jika ada perubahan ----------
            if (empty($changes)) {
                DB::rollBack();
                return response()->json(['message' => 'No changes detected.'], 200);
            }

            // ---------- Reset generated PO file jika ada perubahan ----------
            if (
                !empty($order->po_file_path) || !empty($order->po_file_name)
            ) {
                $changes['po_file_reset'] = [
                    'old_file_name' => $order->po_file_name,
                    'old_file_path' => $order->po_file_path,
                    'new_file_name' => null,
                    'new_file_path' => null,
                ];

                $order->po_file_name = null;
                $order->po_file_path = null;
            }

            $order->save();

            $order->unsetRelation('vendors');
            $order->load('vendors');

            // ---------- Clear cache ----------
            $this->clearOrderCache($order->public_id, $order->po_number);

            // ---------- Insert log ----------
            OrderLogActivity::create([
                'po_number' => $order->po_number,
                'vendor_name' => $order->vendors?->name,
                'payload' => $changes,
                'created_by_user_name' => $user->name,
                'status' => OrderLogActivityStatus::ORDER_UPDATED,
                'description' => generateOrderLogDescription(
                    OrderLogActivityStatus::ORDER_UPDATED,
                    $order->po_number,
                    $user->id
                ),
                'timestamp' => now(),
            ]);

            DB::commit();

            globalLogger('info', 'Order data updated successfully!', [
                'id' => $order->id,
                'changes' => $changes,
            ], 200, 'updateorder');
            return response()->json([
                'message' => 'Order data updated successfully!',
                'data' => $order->fresh(['orderBloodDetails.bloodPacks', 'vendors']),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            globalLogger('error', 'Order data failed to update!', [
                'id' => $id,
                'error' => $e->getMessage(),
            ], 500, 'updateorder');
            return response()->json(['message' => 'Failed to update order data!'], 500);
        }
    }

    // ---------- Fungsi untuk generate PO File ----------
    public function generatePoFile(string $poNumber)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            // ---------- Ambil data order ----------
            $order = OrderBlood::where('po_number', $poNumber)
                ->with([
                    'vendors:id,public_id,name,address',
                    'users:id,public_id,name',
                    'users.roles',
                    'orderBloodDetails:id,public_id,order_blood_id,blood_pack_id,quantity,note',
                    'orderBloodDetails.bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
                ])
                ->firstOrFail();

            $fileName = "PO_FILE-{$poNumber}.pdf";
            $directory = "history_order/po_file";
            $filePath = "{$directory}/{$fileName}";

            // ---------- Jika sudah ada, langsung download ----------
            if ($order->po_file_path && Storage::disk('public')->exists($order->po_file_path)) {
                $order->increment('po_file_download_count');

                $absolutePath = Storage::disk('public')->path($order->po_file_path);

                return response()->download($absolutePath, $fileName, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                ]);
            }

            // ---------- Pastikan direktori ada ----------
            Storage::disk('public')->makeDirectory($directory);

            // ---------- Generate PDF dari blade ----------
            $pdf = Pdf::loadView('pdf.history_order.po_file', [
                'order' => $order,
                'details' => $order->orderBloodDetails,
                'vendor' => $order->vendors,
            ])->setPaper('a4', 'portrait');

            $pdfContent = $pdf->output();

            // ---------- Simpan file ke storage ----------
            Storage::disk('public')->put($filePath, $pdfContent);

            // ---------- Update model OrderBlood ----------
            $order->update([
                'po_file_path' => $filePath,
                'po_file_name' => $fileName,
                'po_file_download_count' => 1,
            ]);

            // ---------- Clear cache agar data terbaru ----------
            $this->clearOrderCache($order->public_id, $poNumber);

            // ---------- Insert data to log ----------
            OrderLogActivity::create([
                'po_number' => $poNumber,
                'vendor_name' => $order->vendors->name,
                'order_data' => $order->toArray(),
                'order_blood_data' => null,
                'created_by_user_name' => $user->name,
                'status' => OrderLogActivityStatus::PO_FILE_GENERATED,
                'description' => generateOrderLogDescription(
                    OrderLogActivityStatus::PO_FILE_GENERATED,
                    $poNumber,
                    $user->id
                ),
                'timestamp' => now(),
                'po_file_path' => $filePath,
                'po_file_name' => $fileName,
            ]);

            DB::commit();

            // ---------- Log sukses ----------
            globalLogger('info', 'PO File generated successfully!', [
                'po_number' => $poNumber,
                'file_path' => $filePath,
            ], 200, 'generatepofile');

            // ---------- Return sebagai download ----------
            return response()->download(
                Storage::disk('public')->path($filePath),
                $fileName,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                ]
            );
        } catch (\Throwable $e) {
            // ---------- Batalkan transaksi database jika ada error ----------
            DB::rollBack();

            // ---------- Masukkan ke log untuk error ----------
            globalLogger('error', 'PO File failed to generate!', [
                'po_number' => $poNumber,
                'error' => $e->getMessage(),
            ], 500, 'generatepofile');

            // ---------- Lempar error respon ke frontend ----------
            return response()->json([
                'message' => 'New order data failed to insert!',
            ], 500);
        }
    }

    // ---------- Fungsi untuk download PO File (wajib sudah ada, tidak generate baru) ----------
    public function downloadPoFile(string $poNumber)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            // ---------- Ambil data order ----------
            $order = OrderBlood::where('po_number', $poNumber)
                ->with([
                    'vendors:id,public_id,name,address',
                    'users:id,public_id,name',
                    'users.roles',
                    'orderBloodDetails:id,public_id,order_blood_id,blood_pack_id,quantity,note',
                    'orderBloodDetails.bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
                ])
                ->firstOrFail();

            // ---------- Validasi: file harus sudah pernah di-generate ----------
            if (!$order->po_file_path || !Storage::disk('public')->exists($order->po_file_path)) {
                return response()->json([
                    'message' => 'PO File not found! Please generate the PO File first.',
                ], 404);
            }

            $fileName = $order->po_file_name ?? "PO_FILE-{$poNumber}.pdf";
            $absolutePath = Storage::disk('public')->path($order->po_file_path);

            // ---------- Increment download count ----------
            $order->increment('po_file_download_count');

            // ---------- Insert data to log ----------
            OrderLogActivity::create([
                'po_number' => $poNumber,
                'vendor_name' => $order->vendors->name,
                'order_data' => $order->toArray(),
                'order_blood_data' => null,
                'created_by_user_name' => $user->name,
                'status' => OrderLogActivityStatus::PO_FILE_DOWNLOADED,
                'description' => generateOrderLogDescription(
                    OrderLogActivityStatus::PO_FILE_DOWNLOADED,
                    $poNumber,
                    $user->id
                ),
                'timestamp' => now(),
                'po_file_path' => $order->po_file_path,
                'po_file_name' => $fileName,
            ]);

            DB::commit();
            // ---------- Log aktivitas download ----------
            globalLogger('info', 'PO File downloaded successfully!', [
                'po_number' => $poNumber,
                'file_path' => $order->po_file_path,
                'download_count' => $order->po_file_download_count,
            ], 200, 'downloadpofile');

            return response()->download($absolutePath, $fileName, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);
        } catch (\Throwable $e) {
            // ---------- Batalkan transaksi database jika ada error ----------
            DB::rollBack();

            // ---------- Masukkan ke log untuk error ----------
            globalLogger('error', 'PO File failed to download!', [
                'po_number' => $poNumber,
                'error' => $e->getMessage(),
            ], 500, 'downloadpofile');

            // ---------- Lempar error respon ke frontend ----------
            return response()->json([
                'message' => 'PO File failed to download!',
            ], 500);
        }
    }

    // ---------- Fungsi untuk print PO File (wajib sudah ada, tidak generate baru) :begin ----------
    public function printPoFile(string $poNumber)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            // ---------- Ambil data order ----------
            $order = OrderBlood::where('po_number', $poNumber)
                ->with([
                    'vendors:id,public_id,name,address',
                    'users:id,public_id,name',
                    'users.roles',
                    'orderBloodDetails:id,public_id,order_blood_id,blood_pack_id,quantity,note',
                    'orderBloodDetails.bloodPacks:id,public_id,blood_group,blood_rhesus,blood_component',
                ])
                ->firstOrFail();

            // ---------- Validasi: file harus sudah pernah di-generate ----------
            if (!$order->po_file_path || !Storage::disk('public')->exists($order->po_file_path)) {
                return response()->json([
                    'message' => 'PO File not found! Please generate the PO File first.',
                ], 404);
            }

            $fileName = $order->po_file_name ?? "PO_FILE-{$poNumber}.pdf";
            $absolutePath = Storage::disk('public')->path($order->po_file_path);

            // ---------- Increment print count ----------
            $order->increment('po_file_print_count');

            // ---------- Insert data to log ----------
            OrderLogActivity::create([
                'po_number' => $poNumber,
                'vendor_name' => $order->vendors->name,
                'order_data' => $order->toArray(),
                'order_blood_data' => null,
                'created_by_user_name' => $user->name,
                'status' => OrderLogActivityStatus::PO_FILE_PRINTED,
                'description' => generateOrderLogDescription(
                    OrderLogActivityStatus::PO_FILE_PRINTED,
                    $poNumber,
                    $user->id
                ),
                'timestamp' => now(),
                'po_file_path' => $order->po_file_path,
                'po_file_name' => $fileName,
            ]);

            DB::commit();
            // ---------- Log aktivitas download ----------
            globalLogger('info', 'PO File printed successfully!', [
                'po_number' => $poNumber,
                'file_path' => $order->po_file_path,
                'print_count' => $order->po_file_print_count,
            ], 200, 'printpofile');

            return response()->download($absolutePath, $fileName, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);
        } catch (\Throwable $e) {
            // ---------- Batalkan transaksi database jika ada error ----------
            DB::rollBack();

            // ---------- Masukkan ke log untuk error ----------
            globalLogger('error', 'PO File failed to print!', [
                'po_number' => $poNumber,
                'error' => $e->getMessage(),
            ], 500, 'printpofile');

            // ---------- Lempar error respon ke frontend ----------
            return response()->json([
                'message' => 'PO File failed to print!',
            ], 500);
        }
    }

    // ---------- Fungsi untuk mengubah status order menjadi done ----------
    public function setOrderDone(string $poNumber)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            // ---------- Ambil data order ----------
            $order = OrderBlood::where('po_number', $poNumber)
                ->where('status', OrderBloodStatus::ALL_ORDER_STOCK_REGISTERED)
                ->with('vendors')
                ->first();

            if (!$order) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Order cannot be completed because all stock is not registered.',
                ], 422);
            }

            // ---------- Update model OrderBlood ----------
            $order->update([
                'status' => OrderBloodStatus::DONE,
            ]);

            // ---------- Clear cache agar data terbaru ----------
            $this->clearOrderCache($order->public_id, $poNumber);

            // ---------- Insert data to log ----------
            OrderLogActivity::create([
                'po_number' => $poNumber,
                'vendor_name' => $order->vendors->name,
                'order_data' => $order->toArray(),
                'order_blood_data' => null,
                'created_by_user_name' => $user->name,
                'status' => OrderLogActivityStatus::ORDER_DONE,
                'description' => generateOrderLogDescription(
                    OrderLogActivityStatus::ORDER_DONE,
                    $poNumber,
                    $user->id
                ),
                'timestamp' => now(),
            ]);

            DB::commit();

            // ---------- Log sukses ----------
            globalLogger('info', 'Order set to done successfully!', [
                'po_number' => $poNumber,
            ], 200, 'setorderdone');

            // ---------- Return sebagai download ----------
            return response()->json([
                'message' => 'Order set to done successfully!',
                'data' => $order->fresh(['orderBloodDetails.bloodPacks', 'vendors']),
            ]);
        } catch (\Throwable $e) {
            // ---------- Batalkan transaksi database jika ada error ----------
            DB::rollBack();

            // ---------- Masukkan ke log untuk error ----------
            globalLogger('error', 'Order failed set to done!', [
                'po_number' => $poNumber,
                'error' => $e->getMessage(),
            ], 500, 'setorderdone');

            // ---------- Lempar error respon ke frontend ----------
            return response()->json([
                'message' => 'Order failed set to done!',
            ], 500);
        }
    }

    // ---------- Fungsi untuk menghapus order ----------
    public function deleteOrder(string $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            // ---------- Status yang boleh dihapus ----------
            $deletableStatuses = [
                OrderBloodStatus::DRAFT,
                OrderBloodStatus::ORDER_CREATED,
            ];

            // ---------- Ambil data order ----------
            $order = OrderBlood::where('public_id', $id)
                ->whereIn('status', $deletableStatuses)
                ->with('vendors')
                ->first();

            // ---------- Validasi order ----------
            if (!$order) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Order cannot be deleted because current status is not allowed.',
                ], 422);
            }

            $order->update([
                'status' => OrderBloodStatus::ORDER_DELETED,
            ]);

            // ---------- Soft delete order ----------
            $order->delete();

            // ---------- Clear cache ----------
            $this->clearOrderCache($order->public_id, $order->po_number);

            // ---------- Insert log ----------
            OrderLogActivity::create([
                'po_number' => $order->po_number,
                'vendor_name' => $order->vendors?->name,
                'payload' => [
                    'deleted_order' => [
                        'po_number' => $order->po_number,
                        'vendor' => $order->vendors?->name,
                        'status' => $order->status->value,
                        'total_quantity' => $order->total_quantity,
                    ],
                ],
                'created_by_user_name' => $user->name,
                'status' => OrderLogActivityStatus::ORDER_DELETED,
                'description' => generateOrderLogDescription(
                    OrderLogActivityStatus::ORDER_DELETED,
                    $order->po_number,
                    $user->id
                ),
                'timestamp' => now(),
                'deleted_at' => now(),
            ]);

            DB::commit();

            // ---------- Log sukses ----------
            globalLogger('info', 'Order deleted successfully!', [
                'po_number' => $order->po_number,
            ], 200, 'deleteorder');

            return response()->json([
                'message' => 'Order deleted successfully!',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            // ---------- Log error ----------
            globalLogger('error', 'Order failed to delete!', [
                'error' => $e->getMessage(),
            ], 500, 'deleteorder');

            return response()->json([
                'message' => 'Order failed to delete!',
            ], 500);
        }
    }

    // ---------- Fungsi untuk memulihkan order ----------
    public function restoreOrder(string $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            // ---------- Ambil data order ----------
            $order = OrderBlood::withTrashed()->where('public_id', $id)
                ->where('status', OrderBloodStatus::ORDER_DELETED)
                ->with('vendors')
                ->first();

            // ---------- Validasi order ----------
            if (!$order) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Order cannot be restored because current status is not allowed.',
                ], 422);
            }

            $order->update([
                'status' => OrderBloodStatus::ORDER_CREATED,
            ]);

            // ---------- Soft delete order ----------
            $order->restore();

            // ---------- Clear cache ----------
            $this->clearOrderCache($order->public_id, $order->po_number);

            // ---------- Insert log ----------
            OrderLogActivity::create([
                'po_number' => $order->po_number,
                'vendor_name' => $order->vendors?->name,
                'payload' => [
                    'restored_order' => [
                        'po_number' => $order->po_number,
                        'vendor' => $order->vendors?->name,
                        'status' => $order->status->value,
                        'total_quantity' => $order->total_quantity,
                    ],
                ],
                'created_by_user_name' => $user->name,
                'status' => OrderLogActivityStatus::ORDER_RESTORED,
                'description' => generateOrderLogDescription(
                    OrderLogActivityStatus::ORDER_RESTORED,
                    $order->po_number,
                    $user->id
                ),
                'timestamp' => now(),
                'deleted_at' => now(),
            ]);

            DB::commit();

            // ---------- Log sukses ----------
            globalLogger('info', 'Order restored successfully!', [
                'po_number' => $order->po_number,
            ], 200, 'restoredorder');
            return response()->json([
                'message' => 'Order restored successfully!',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            // ---------- Log error ----------
            globalLogger('error', 'Order failed to restore!', [
                'error' => $e->getMessage(),
            ], 500, 'restoredorder');
            return response()->json([
                'message' => 'Order failed to restore!',
            ], 500);
        }
    }

    // ---------- Clear Cache ----------
    public function clearOrderCache(string $id, string $poNumber)
    {
        Cache::forget(self::CACHE_ORDER_BY_ID_KEY . ":{$id}");
        Cache::forget(self::CACHE_ORDER_BY_PO_KEY . ":{$poNumber}");
    }
}
