<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MayorsReferral extends Model
{
    public const TYPE_PESO_OFFICE = 'peso_office';

    public const TYPE_OTHER_CITY_GOVERNMENT = 'other_city_government';

    public const APPROVAL_PENDING = 'pending';

    public const APPROVAL_APPROVED = 'approved';

    public const APPROVAL_DISAPPROVED = 'disapproved';

    protected $fillable = [
        'applicant_id',
        'approval_status',
        'disapproval_reason',
        'referral_type',
        'resume',
        'biodata',
        'ref_barangay_clearance',
        'ref_police_clearance',
        'ref_nbi_clearance',

        'ref_imus_ocrl',
        'ref_employer_name',
        'ref_position',
        'ref_company_address',
        'ref_hired_company',
        'ref_province',

        'ref_recipient',
        'ref_place',
        'ref_ocrl',
        'ref_city_gov',
        'referral_details',
    ];

    protected $casts = [
        'referral_details' => 'array',
    ];

    public static function generateNextSequence(string $column, ?int $year = null): string
    {
        $year ??= (int) Carbon::now()->format('Y');
        $prefix = $year.'-';

        $latestNumber = static::query()
            ->where($column, 'like', $prefix.'%')
            ->pluck($column)
            ->map(function ($value) use ($year) {
                if (! preg_match('/^'.preg_quote((string) $year, '/')."-(\d{5})$/", (string) $value, $matches)) {
                    return 0;
                }

                return (int) $matches[1];
            })
            ->max() ?: 0;

        $nextNumber = $latestNumber + 1;

        if ($nextNumber > 99999) {
            $nextNumber = 1;
        }

        return sprintf('%d-%05d', $year, $nextNumber);
    }

    public static function generateNextOcrl(?int $year = null): string
    {
        return static::generateNextSequence('ref_ocrl', $year);
    }

    public static function generateNextImusOcrl(?int $year = null): string
    {
        $year ??= (int) Carbon::now()->format('Y');
        $prefix = $year.'-';

        $candidates = static::query()
            ->get(['ref_imus_ocrl', 'referral_details'])
            ->flatMap(function (self $referral) {
                $details = is_array($referral->referral_details ?? null) ? $referral->referral_details : [];

                return collect([$referral->ref_imus_ocrl])
                    ->merge(
                        collect($details)->map(function ($detail) {
                            $detail = is_array($detail) ? $detail : [];

                            return $detail['ref_imus_ocrl'] ?? null;
                        })
                    );
            })
            ->filter(fn ($value) => is_string($value) && preg_match('/^\d{4}-\d{5}$/', $value))
            ->values();

        $highestNumber = 0;

        foreach ($candidates as $candidate) {
            if (preg_match('/^(\d{4})-(\d{5})$/', $candidate, $matches) && (int) $matches[1] === $year) {
                $highestNumber = max($highestNumber, (int) $matches[2]);
            }
        }

        return sprintf('%d-%05d', $year, $highestNumber + 1);
    }

    public function hasRequiredDetails(): bool
    {
        if ($this->referral_type === self::TYPE_PESO_OFFICE) {
            return ! empty($this->ref_employer_name)
                && ! empty($this->ref_position)
                && ! empty($this->ref_place)
                && ! empty($this->ref_province)
                && ! empty($this->ref_hired_company);
        }

        if ($this->referral_type === self::TYPE_OTHER_CITY_GOVERNMENT) {
            return ! empty($this->ref_recipient)
                && ! empty($this->ref_company_address)
                && ! empty($this->ref_city_gov);
        }

        return false;
    }

    public static function hasPesoDetailRequirements(array $detail): bool
    {
        return ! empty(trim((string) ($detail['ref_employer_name'] ?? '')))
            && ! empty(trim((string) ($detail['ref_position'] ?? '')))
            && ! empty(trim((string) ($detail['ref_place'] ?? '')))
            && ! empty(trim((string) ($detail['ref_province'] ?? '')))
            && ! empty(trim((string) ($detail['ref_hired_company'] ?? '')));
    }

    public static function hasPrintablePesoDetail(array $detail): bool
    {
        return static::hasPesoDetailRequirements($detail)
            && ! empty(trim((string) ($detail['ref_imus_ocrl'] ?? '')));
    }

    public function hasCompletePesoExtraDetails(): bool
    {
        if ($this->referral_type !== self::TYPE_PESO_OFFICE) {
            return true;
        }

        $supplementaryDetails = array_values(array_slice($this->referral_details ?? [], 1));

        foreach ($supplementaryDetails as $detail) {
            $detail = is_array($detail) ? $detail : [];

            if (collect($detail)->contains(fn ($value) => trim((string) $value) !== '') && ! static::hasPrintablePesoDetail($detail)) {
                return false;
            }
        }

        return true;
    }

    public function canPrint(): bool
    {
        return $this->isApproved() && $this->isComplete();
    }

    public function isApproved(): bool
    {
        return ($this->approval_status ?? self::APPROVAL_APPROVED) === self::APPROVAL_APPROVED;
    }

    public function approvalStatusLabel(): string
    {
        return ucfirst((string) ($this->approval_status ?: self::APPROVAL_APPROVED));
    }

    public function approvalStatusClass(): string
    {
        return match ($this->approval_status ?? self::APPROVAL_APPROVED) {
            self::APPROVAL_PENDING => 'text-bg-warning',
            self::APPROVAL_APPROVED => 'text-bg-success',
            self::APPROVAL_DISAPPROVED => 'text-bg-danger',
            default => 'text-bg-success',
        };
    }

    public function approvalStatusMessage(): string
    {
        return match ($this->approval_status ?? self::APPROVAL_APPROVED) {
            self::APPROVAL_PENDING => 'Awaiting admin or staff approval.',
            self::APPROVAL_DISAPPROVED => 'Disapproved by admin or staff.',
            default => 'Approved by admin or staff.',
        };
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function isComplete()
    {
        if (! in_array($this->referral_type, [self::TYPE_PESO_OFFICE, self::TYPE_OTHER_CITY_GOVERNMENT], true)) {
            return false;
        }

        $hasProfile = ! empty($this->resume) || ! empty($this->biodata);
        $hasClearance = ! empty($this->ref_barangay_clearance)
            || ! empty($this->ref_police_clearance)
            || ! empty($this->ref_nbi_clearance);

        return $this->isApproved()
            && $hasProfile
            && $hasClearance
            && $this->hasRequiredDetails()
            && $this->hasCompletePesoExtraDetails();
    }
}
