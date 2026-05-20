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

    public const ROLE_STAFF = 'staff';

    public const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'permissions',
        'auth_provider',
        'profile_image',
        'applicant_id',
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

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function linkedApplicant(): ?Applicant
    {
        if ($this->relationLoaded('applicant') && $this->getRelation('applicant')) {
            return $this->getRelation('applicant');
        }

        if (! empty($this->applicant_id)) {
            return $this->applicant;
        }

        return null;
    }

    public static function roles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_STAFF,
            self::ROLE_ADMIN,
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
            'approve_document' => 'Approve Submitted Documents',
            'auto_approve_uploaded_files' => 'Auto-Approve Uploaded Files',
            'view_archive_applicants' => 'View Archived Applicants',
            'restore_archive_applicants' => 'Restore Archived Applicants',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function canAutoApproveUploadedFiles(): bool
    {
        return $this->isAdmin()
            || $this->isStaff()
            || $this->hasPermission('auto_approve_uploaded_files');
    }

    public function canViewReferralLetter(): bool
    {
        return $this->isAdmin()
            || $this->isStaff()
            || $this->hasPermission('generate_referral')
            || $this->hasPermission('approve_document')
            || ($this->role === self::ROLE_USER
                && $this->linkedApplicant()?->referral?->canPrint());
    }

    public function roleLabel(): string
    {
        return ucfirst((string) ($this->role ?: self::ROLE_USER));
    }

    public function roleBadgeClass(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'role-pill-admin',
            self::ROLE_STAFF => 'role-pill-staff',
            default => 'role-pill-user',
        };
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

        if (filter_var($this->profile_image, FILTER_VALIDATE_URL)) {
            return $this->profile_image;
        }

        // Prefer checking the storage disk directly instead of relying on a public
        // symlink to `public/storage`. This ensures the existence check works
        // in environments where `php artisan storage:link` hasn't been run.
        if (! Storage::disk('public')->exists($this->profile_image)) {
            return null;
        }

        return Storage::disk('public')->url($this->profile_image);
    }
}
