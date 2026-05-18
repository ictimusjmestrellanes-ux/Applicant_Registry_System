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

    public const APPROVAL_PENDING = 'pending';

    public const APPROVAL_APPROVED = 'approved';

    public const APPROVAL_DISAPPROVED = 'disapproved';

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
        'approval_status',
        'disapproval_reason',
        'disapproval_notes',
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

    public static function roles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_STAFF,
            self::ROLE_ADMIN,
        ];
    }

    public static function approvalStatuses(): array
    {
        return [
            self::APPROVAL_PENDING,
            self::APPROVAL_APPROVED,
            self::APPROVAL_DISAPPROVED,
        ];
    }

    public static function permissionOptions(): array
    {
        return [
            'approve_applicant' => 'Approve Applicant Accounts',
            'approve_document' => 'Approve Submitted Documents',
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

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
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

    public function isPendingApproval(): bool
    {
        return ($this->approval_status ?? self::APPROVAL_APPROVED) === self::APPROVAL_PENDING;
    }

    public function isDisapproved(): bool
    {
        return ($this->approval_status ?? self::APPROVAL_APPROVED) === self::APPROVAL_DISAPPROVED;
    }

    public function isAccountBlocked(): bool
    {
        return in_array(
            $this->approval_status ?? self::APPROVAL_APPROVED,
            [self::APPROVAL_PENDING, self::APPROVAL_DISAPPROVED],
            true
        );
    }

    public function approvalStatusLabel(): string
    {
        return ucfirst((string) ($this->approval_status ?: self::APPROVAL_APPROVED));
    }

    public function approvalStatusBadgeClass(): string
    {
        return match ($this->approval_status ?? self::APPROVAL_APPROVED) {
            self::APPROVAL_PENDING => 'approval-pill-pending',
            self::APPROVAL_APPROVED => 'approval-pill-approved',
            self::APPROVAL_DISAPPROVED => 'approval-pill-disapproved',
            default => 'approval-pill-approved',
        };
    }

    public function approvalStatusMessage(): string
    {
        return match ($this->approval_status ?? self::APPROVAL_APPROVED) {
            self::APPROVAL_PENDING => 'Your account is pending admin approval. Please wait for the administrator to approve it before signing in.',
            self::APPROVAL_DISAPPROVED => $this->approvalDisapprovedMessage(),
            default => 'Your account is pending admin approval. Please wait for the administrator to approve it before signing in.',
        };
    }

    public function approvalDisapprovedMessage(): string
    {
        $reason = trim((string) ($this->disapproval_reason ?? ''));

        if ($reason !== '') {
            return 'Your account was disapproved by an administrator. Reason: ' . $reason;
        }

        return 'Your account was disapproved by an administrator. Please contact the office for assistance.';
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

        // Prefer checking the storage disk directly instead of relying on a public
        // symlink to `public/storage`. This ensures the existence check works
        // in environments where `php artisan storage:link` hasn't been run.
        if (! Storage::disk('public')->exists($this->profile_image)) {
            return null;
        }

        return Storage::disk('public')->url($this->profile_image);
    }
}
