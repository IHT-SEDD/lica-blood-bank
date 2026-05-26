<?php

namespace App\Services\BloodTransfusion;

use App\Enums\BloodStockStatus;
use App\Enums\BloodTransfusionStatus;
use App\Models\BloodStock;
use App\Models\BloodTransfusion;
use App\Models\BloodTransfusionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BloodTransfusionWriteService
{
    // ---------- Fungsi Check In ----------
    public function checkinTransaction(string $publicId): string
    {
        return DB::transaction(function () use ($publicId) {
            $transfusion = BloodTransfusion::where('public_id', $publicId)
                ->lockForUpdate()
                ->firstOrFail();
            if ($transfusion->lab_number) {
                throw new \RuntimeException(
                    'This request has already been checked in.'
                );
            }

            $datePrefix = now()->format('ymd');

            $lock = Cache::lock('generate_lab_number', 10);
            if (!$lock->get()) {
                throw new \RuntimeException(
                    'System is currently processing another request, please try again in a moment.'
                );
            }

            try {
                $latestLabNumber = BloodTransfusion::where('lab_number', 'like', $datePrefix . '%')
                    ->lockForUpdate()
                    ->orderBy('lab_number', 'desc')
                    ->value('lab_number');

                $nextSequence = $latestLabNumber ? ((int) substr($latestLabNumber, -3)) + 1 : 1;
                $labNumber = $datePrefix . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

                $transfusion->update([
                    'lab_number' => $labNumber,
                    'status' => BloodTransfusionStatus::BLOOD_TRANSFUSION_CHECKED_IN ?? $transfusion->status,
                    'checkin_by_user_id' => Auth::id(),
                ]);

                return $labNumber;
            } finally {
                $lock->release();
            }
        });
    }


    // ---------- Fungsi Hold Blood Pack ----------
    public function completeTransaction(string $publicId): void
    {
        DB::transaction(function () use ($publicId) {
            $transfusion = BloodTransfusion::where('public_id', $publicId)
                ->lockForUpdate()
                ->firstOrFail();
            if ($transfusion->finish_at) {
                throw new \RuntimeException('This request has been completed');
            }

            $lock = Cache::lock('complete_transaction', 10);
            if (!$lock->get()) {
                throw new \RuntimeException('System is currently processing another request, please try again in a moment.');
            }

            try {
                $transfusion->update([
                    'finish_at' => now(),
                    'status' => BloodTransfusionStatus::BLOOD_TRANSFUSION_FINISHED ?? $transfusion->status,
                    'finish_by_user_id' => Auth::id(),
                ]);
            } finally {
                $lock->release();
            }
        });
    }

    // ---------- Fungsi Hold Blood Pack ----------
    public function holdBloodPack(string $detailPublicId): void
    {
        DB::transaction(function () use ($detailPublicId) {
            $detail = $this->getLockedDetail($detailPublicId);
            if (!$detail->blood_stock_id) {
                throw new \RuntimeException('Blood stock not assigned to this detail.');
            }
            $this->updateBloodStockStatus($detail->blood_stock_id, BloodStockStatus::HOLD);
        });
    }

    // ---------- Fungsi Release Blood Pack ----------
    public function releaseBloodPack(string $detailPublicId): void
    {
        DB::transaction(function () use ($detailPublicId) {
            $detail = $this->getLockedDetail($detailPublicId, [
                ['blood_release_status', false],
            ]);
            if (!$detail->blood_stock_id) {
                throw new \RuntimeException('Blood stock not assigned to this detail.');
            }
            $detail->update(['blood_release_status' => true]);
            $this->updateBloodStockStatus($detail->blood_stock_id, BloodStockStatus::TAKEN_OUT);
        });
    }

    // ---------- Fungsi UnRelease Blood Pack ----------
    public function unReleaseBloodPack(string $detailPublicId): void
    {
        DB::transaction(function () use ($detailPublicId) {
            $detail = $this->getLockedDetail($detailPublicId, [
                ['blood_release_status', false],
            ]);
            if (!$detail->blood_stock_id) {
                throw new \RuntimeException('Blood stock not assigned to this detail.');
            }
            $this->updateBloodStockStatus($detail->blood_stock_id, BloodStockStatus::USED);
        });
    }

    // ---------- Fungsi Approve Incompatible ----------
    public function approveIncompatible(string $detailPublicId): void
    {
        DB::transaction(function () use ($detailPublicId) {
            $detail = $this->getLockedDetail($detailPublicId, [
                ['blood_release_status', false],
                ['is_print_incompatible_letter', true],
                ['is_approval_incompatible', false],
            ]);

            $detail->update(['is_approval_incompatible' => true]);
        });
    }

    // ---------- Helpers ----------
    private function getLockedDetail(string $detailPublicId, array $conditions = []): BloodTransfusionDetail
    {
        return BloodTransfusionDetail::where('public_id', $detailPublicId)
            ->when(!empty($conditions), fn($q) => $q->where($conditions))
            ->lockForUpdate()
            ->firstOrFail();
    }
    private function updateBloodStockStatus(int $bloodStockId, BloodStockStatus $status): void
    {
        BloodStock::where('id', $bloodStockId)
            ->lockForUpdate()
            ->firstOrFail()
            ->update(['blood_status' => $status]);
    }
}
