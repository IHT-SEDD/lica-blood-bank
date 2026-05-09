<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property \App\Enums\BloodGroup $blood_group
 * @property string $blood_rhesus
 * @property \App\Enums\BloodComponent $blood_component
 * @property int $warning_quantity
 * @property int $danger_quantity
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodStock> $bloodStocks
 * @property-read int|null $blood_stocks_count
 * @property-read mixed $label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack whereBloodComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack whereBloodRhesus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack whereDangerQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack whereWarningQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodPack withoutTrashed()
 */
	class BloodPack extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property string $bag_number
 * @property string|null $bag_number_lica
 * @property int|null $incoming_blood_detail_id
 * @property int|null $blood_pack_id
 * @property int $blood_volume
 * @property string $aftap_date
 * @property string $process_date
 * @property string $expiry_date
 * @property int $is_hiv
 * @property int $is_hbsag
 * @property int $is_hcv
 * @property int $is_syphilis
 * @property int $is_expired
 * @property string|null $blood_status
 * @property string|null $used_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \App\Enums\BloodGroup $blood_group
 * @property \App\Enums\BloodComponent $blood_component
 * @property-read \App\Models\BloodPack|null $bloodPacks
 * @property-read \App\Models\IncomingBloodDetail|null $incomingBloodDetails
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereAftapDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereBagNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereBagNumberLica($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereBloodPackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereBloodStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereBloodVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereIncomingBloodDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereIsExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereIsHbsag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereIsHcv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereIsHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereIsSyphilis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereProcessDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock whereUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStock withoutTrashed()
 */
	class BloodStock extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property string|null $blood_stock_public_id
 * @property array<array-key, mixed>|null $payload
 * @property string|null $status
 * @property string|null $description
 * @property string|null $created_by_user_name
 * @property string|null $timestamp
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity whereBloodStockPublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity whereCreatedByUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodStockLogActivity whereUpdatedAt($value)
 */
	class BloodStockLogActivity extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int|null $patient_id
 * @property int|null $insurance_id
 * @property int|null $room_id
 * @property int|null $doctor_id
 * @property string|null $lab_number
 * @property string|null $order_number
 * @property string|null $relation_name
 * @property string|null $relation_type
 * @property string $blood_request_at
 * @property string|null $diagnosis
 * @property string|null $finish_at
 * @property string $status
 * @property int $blood_quantity
 * @property int|null $checkin_by_user_id
 * @property int|null $finish_by_user_id
 * @property int|null $deleted_by_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BloodTransfusionDetail> $details
 * @property-read int|null $details_count
 * @property-read \App\Models\Patient|null $patient
 * @property-read \App\Models\Room|null $room
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereBloodQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereBloodRequestAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereCheckinByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereDeletedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereDiagnosis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereFinishAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereFinishByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereInsuranceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereLabNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereRelationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereRelationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusion withoutTrashed()
 */
	class BloodTransfusion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int|null $blood_transfusion_id
 * @property int|null $blood_stock_id
 * @property string|null $transfusion_text
 * @property string|null $transfusion_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\BloodStock|null $bloodStock
 * @property-read \App\Models\BloodTransfusion|null $bloodTransfusion
 * @property-read \App\Models\BloodTransfusionDetailTest|null $bloodTransfusionDetailTest
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail whereBloodStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail whereBloodTransfusionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail whereTransfusionAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail whereTransfusionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetail withoutTrashed()
 */
	class BloodTransfusionDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int|null $bt_detail_id
 * @property int|null $test_id
 * @property int|null $package_id
 * @property string|null $type
 * @property string|null $result
 * @property string|null $result_status
 * @property int|null $result_by_user_id
 * @property int|null $verified_by_user_id
 * @property string|null $verified_at
 * @property int|null $validated_by_user_id
 * @property string|null $validated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\BloodTransfusionDetail|null $bloodTransfusionDetail
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereBtDetailId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereResultByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereResultStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereTestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereValidatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereValidatedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest whereVerifiedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BloodTransfusionDetailTest withoutTrashed()
 */
	class BloodTransfusionDetailTest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int $is_active
 * @property string $name
 * @property string|null $general_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereGeneralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Doctor withoutTrashed()
 */
	class Doctor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int|null $order_blood_id
 * @property string|null $po_number
 * @property string|null $batch_number
 * @property \App\Enums\IncomingBloodStatus $status
 * @property int|null $received_by_user_id
 * @property string|null $received_at
 * @property int|null $registered_by_user_id
 * @property string|null $stock_ready_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\IncomingBloodDetail> $incomingBloodDetails
 * @property-read int|null $incoming_blood_details_count
 * @property-read \App\Models\OrderBlood|null $orderBloods
 * @property-read \App\Models\User|null $users
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereBatchNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereOrderBloodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood wherePoNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereReceivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereReceivedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereRegisteredByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereStockReadyAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBlood withoutTrashed()
 */
	class IncomingBlood extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int|null $incoming_blood_id
 * @property string $bag_number
 * @property int|null $blood_pack_id
 * @property int $blood_volume
 * @property string $aftap_date
 * @property string $process_date
 * @property string $expiry_date
 * @property int $is_hiv
 * @property int $is_hbsag
 * @property int $is_hcv
 * @property int $is_syphilis
 * @property int $is_expired
 * @property int $is_ready
 * @property string|null $ready_at
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\BloodPack|null $bloodPacks
 * @property-read \App\Models\IncomingBlood|null $incomingBloods
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereAftapDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereBagNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereBloodPackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereBloodVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereIncomingBloodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereIsExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereIsHbsag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereIsHcv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereIsHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereIsReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereIsSyphilis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereProcessDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereReadyAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodDetail withoutTrashed()
 */
	class IncomingBloodDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property string|null $incoming_blood_public_id
 * @property string|null $po_number
 * @property string|null $batch_number
 * @property array<array-key, mixed>|null $incoming_data
 * @property array<array-key, mixed>|null $blood_data
 * @property string|null $status
 * @property string|null $created_by_user_name
 * @property string|null $description
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereBatchNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereBloodData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereCreatedByUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereIncomingBloodPublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereIncomingData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity wherePoNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncomingBloodLogActivity whereUpdatedAt($value)
 */
	class IncomingBloodLogActivity extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int $is_active
 * @property string $name
 * @property string|null $general_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereGeneralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Insurance withoutTrashed()
 */
	class Insurance extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int|null $vendor_id
 * @property string $po_number
 * @property int $total_quantity
 * @property string|null $description
 * @property int|null $ordered_by_user_id
 * @property \App\Enums\OrderBloodStatus $status
 * @property string|null $po_file_path
 * @property string|null $po_file_name
 * @property int $po_file_print_count
 * @property int $po_file_download_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderBloodDetail> $orderBloodDetails
 * @property-read int|null $order_blood_details_count
 * @property-read \App\Models\User|null $users
 * @property-read \App\Models\Vendor|null $vendors
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood whereOrderedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood wherePoFileDownloadCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood wherePoFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood wherePoFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood wherePoFilePrintCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood wherePoNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood whereTotalQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood whereVendorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBlood withoutTrashed()
 */
	class OrderBlood extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int $order_blood_id
 * @property int|null $blood_pack_id
 * @property string|null $note
 * @property int $is_hiv
 * @property int $is_hbsag
 * @property int $is_hcv
 * @property int $is_syphilis
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\BloodPack|null $bloodPacks
 * @property-read \App\Models\OrderBlood|null $orderBloods
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereBloodPackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereIsHbsag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereIsHcv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereIsHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereIsSyphilis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereOrderBloodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderBloodDetail withoutTrashed()
 */
	class OrderBloodDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property string|null $po_number
 * @property string|null $vendor_name
 * @property array<array-key, mixed>|null $order_data
 * @property array<array-key, mixed>|null $order_blood_data
 * @property string|null $payload
 * @property string|null $created_by_user_name
 * @property string|null $status
 * @property string|null $description
 * @property string|null $timestamp
 * @property string|null $deleted_at
 * @property string|null $po_file_path
 * @property string|null $po_file_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereCreatedByUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereOrderBloodData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereOrderData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity wherePoFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity wherePoFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity wherePoNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderLogActivity whereVendorName($value)
 */
	class OrderLogActivity extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int $is_active
 * @property string $name
 * @property string|null $blood_component
 * @property string|null $general_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PackageTest> $package_tests
 * @property-read int|null $package_tests_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereBloodComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereGeneralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package withoutTrashed()
 */
	class Package extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int $is_active
 * @property int $package_id
 * @property int $test_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Package|null $package
 * @property-read \App\Models\Test|null $test
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest whereTestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageTest withoutTrashed()
 */
	class PackageTest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property string $name
 * @property string|null $medrec
 * @property string $gender
 * @property \Illuminate\Support\Carbon $birthdate
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $blood_group
 * @property string|null $blood_rhesus
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereBloodRhesus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereMedrec($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient withoutTrashed()
 */
	class Patient extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int $is_active
 * @property string $name
 * @property string $type
 * @property string $class
 * @property string|null $general_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereGeneralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room withoutTrashed()
 */
	class Room extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property string $name
 * @property string|null $model
 * @property string|null $serial_number
 * @property string|null $manufacturer
 * @property int $rack_capacity
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StorageRack> $storageRacks
 * @property-read int|null $storage_racks_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage whereRackCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Storage withoutTrashed()
 */
	class Storage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int|null $storage_id
 * @property string $name
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Storage|null $storages
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack whereStorageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageRack withoutTrashed()
 */
	class StorageRack extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property int $is_active
 * @property string $name
 * @property string|null $general_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test whereGeneralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Test withoutTrashed()
 */
	class Test extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $teams
 * @property-read int|null $teams_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, ?string $guard = null, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, ?string $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTeam($teams)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $public_id
 * @property string $name
 * @property string|null $address
 * @property string|null $phone_number
 * @property string|null $telephone_number
 * @property string|null $pic_name
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor wherePicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereTelephoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor withoutTrashed()
 */
	class Vendor extends \Eloquent {}
}

