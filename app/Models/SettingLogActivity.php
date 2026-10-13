<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SettingLogActivity extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'public_id',
        'setting_key',
        'action',
        'old_value',
        'new_value',
        'user_id',
        'user_name',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    protected $hidden = [
        'id',
    ];

    // Log audit tidak boleh diubah atau dihapus lewat Eloquent
    protected static function booted(): void
    {
        static::updating(fn() => false);
        static::deleting(fn() => false);
        static::creating(function ($settingLogActivity) {
            if (empty($settingLogActivity->public_id)) {
                $settingLogActivity->public_id = (string) Str::uuid();
            }
        });
    }

    // Pelaku perubahan
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
