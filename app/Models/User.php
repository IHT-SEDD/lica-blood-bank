<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'public_id',
        'name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Ubah username menjadi huruf kecil & bikin public_id
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->username = strtolower($user->username);

            if (empty($user->public_id)) {
                $user->public_id = (string) Str::uuid();
            }
        });
    }

    // Buat peraturan validasi untuk add data model user
    public static function rules($context = 'store', $id = null)
    {
        return match ($context) {
            'store' => [
                'name' => 'string|required',
                'username' => 'string|required|unique:users,username',
                'password' => 'required|min:5',
            ],
            'update' => [
                'name' => 'sometimes|required|string',
                'username' => "sometimes|required|string|unique:users,username,$id",
                'password' => 'nullable|min:5',
            ]
        };
    }
}
