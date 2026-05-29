<?php

namespace App\Enums;

enum BloodTransfusionLogActivityStatus: string
{
    case REGISTERED = 'blood_transfusion_registered';
    case CHECKED_IN = 'blood_transfusion_checked_in';
    case FINISHED = 'blood_transfusion_finished';
    case COMPLETED = 'blood_transfusion_completed';
    case DELETED = 'blood_transfusion_deleted';
    case ARCHIVED = 'blood_transfusion_archived';
    case UPDATED = 'blood_transfusion_updated';
    case CROSSMATCH_FINISH = 'crossmatch_finished';

    case BLOOD_HOLD = 'blood_stock_hold';
    case BLOOD_RELEASE = 'blood_stock_released';
    case BLOOD_DONT_RELEASE = 'blood_stock_not_released';
    case APPROVE_INCOMPATIBLE = 'blood_stock_approved_incompatible';

    public function label(): string
    {
        return match ($this) {
            self::REGISTERED => '(REGISTERED)',
            self::CHECKED_IN => '(CHECKED IN)',
            self::FINISHED => '(FINISHED)',
            self::COMPLETED => '(COMPLETED)',
            self::DELETED => '(DELETED)',
            self::ARCHIVED => '(ARCHIVED)',
            self::UPDATED => '(UPDATED)',
            self::CROSSMATCH_FINISH => '(CROSSMATCH_FINISHED)',

            self::BLOOD_HOLD => '(BLOOD IN HOLD)',
            self::BLOOD_RELEASE => '(BLOOD RELEASED)',
            self::BLOOD_DONT_RELEASE => '(BLOOD NOT RELEASED)',
            self::APPROVE_INCOMPATIBLE => '(BLOOD APPROVED INCOMPATIBLE)',
        };
    }

    public function template(): string
    {
        return match ($this) {
            self::REGISTERED => 'Blood Transfusion %s: Registered sucessfully by User ID %s.',
            self::CHECKED_IN => 'Blood Transfusion %s: Checked In sucessfully by User ID %s.',
            self::FINISHED => 'Blood Transfusion %s: Finished successfully by User ID %s.',
            self::COMPLETED => 'Blood Transfusion %s: Completed successfully by User ID %s.',
            self::DELETED => 'Blood Transfusion %s: Deleted successfully by User ID %s.',
            self::ARCHIVED => 'Blood Transfusion %s: Archived successfully by User ID %s.',
            self::UPDATED => 'Blood Transfusion %s: Data Updated successfully by User ID %s.',
            self::CROSSMATCH_FINISH => 'Blood Transfusion %s: Crossmatch Finished successfully by User ID %s.',

            self::BLOOD_HOLD => '(Blood Bag) Blood Transfusion %s: Set to Hold successfully by User ID %s.',
            self::BLOOD_RELEASE => '(Blood Bag) Blood Transfusion %s: Released (taken_out) successfully by User ID %s.',
            self::BLOOD_DONT_RELEASE => '(Blood Bag) Blood Transfusion %s: Not Released (used) successfully by User ID %s.',
            self::APPROVE_INCOMPATIBLE => '(Blood Bag) Blood Transfusion %s: Approved incompatible successfully by User ID %s.',
        };
    }
}
