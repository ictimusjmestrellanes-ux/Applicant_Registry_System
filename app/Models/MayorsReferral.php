<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MayorsReferral extends Model
{
    public const TYPE_PESO_OFFICE = 'peso_office';

    public const TYPE_OTHER_CITY_GOVERNMENT = 'other_city_government';

    protected $fillable = [
        'applicant_id',
        'referral_type',
        'resume',
        'biodata',
        'ref_barangay_clearance',
        'ref_police_clearance',
        'ref_nbi_clearance',

        'ref_imus_ocrl',
        'ref_employer_name',
        'ref_position',
        'ref_or_no',
        'ref_company_address',
        'ref_hired_company',

        'ref_peso_or_no',
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

        $latestForYear = static::query()
            ->where($column, 'like', $prefix.'%')
            ->orderByDesc($column)
            ->value($column);

        $nextNumber = 1;

        if ($latestForYear && preg_match('/^\d{4}-(\d{5})$/', $latestForYear, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
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
            return ! empty($this->ref_or_no)
                && ! empty($this->ref_employer_name)
                && ! empty($this->ref_position)
                && ! empty($this->ref_place)
                && ! empty($this->ref_hired_company);
        }

        if ($this->referral_type === self::TYPE_OTHER_CITY_GOVERNMENT) {
            return ! empty($this->ref_peso_or_no)
                && ! empty($this->ref_recipient)
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
        return $this->isComplete();
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

        return $hasProfile && $hasClearance && $this->hasRequiredDetails() && $this->hasCompletePesoExtraDetails();
    }
}
