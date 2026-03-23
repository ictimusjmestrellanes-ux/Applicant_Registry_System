<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'auth_provider',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
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
            'permissions' => 'array',
        ];
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'causer_id');
    }

    public static function roles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_USER,
        ];
    }

    public static function permissionOptions(): array
    {
        return [
            'update_permit' => "Update Mayor's Permit to Work",
            'generate_permit' => "Generate Mayor's Permit to Work ID",
            'update_clearance' => "Update Mayor's Clearance",
            'generate_clearance' => "Generate Mayor's Clearance Letter",
            'update_referral' => "Update Mayor's Referral",
            'generate_referral' => "Generate Mayor's Referral Letter",
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($permission, $this->permissions ?? [], true);
    }

    public function profileImageUrl(): ?string
    {
        if (! $this->profile_image) {
            return null;
        }

        $publicStoragePath = public_path('storage/' . ltrim($this->profile_image, '/'));

        if (! File::exists($publicStoragePath)) {
            return null;
        }

        return Storage::disk('public')->url($this->profile_image);
    }
}
