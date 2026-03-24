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
    ];

    public static function generateNextOcrl(?int $year = null): string
    {
        $year ??= (int) Carbon::now()->format('Y');
        $prefix = $year.'-';

        $latestForYear = static::query()
            ->where('ref_ocrl', 'like', $prefix.'%')
            ->orderByDesc('ref_ocrl')
            ->value('ref_ocrl');

        $nextNumber = 1;

        if ($latestForYear && preg_match('/^\d{4}-(\d{3})$/', $latestForYear, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        return sprintf('%d-%03d', $year, $nextNumber);
    }

    public function hasRequiredDetails(): bool
    {
        if ($this->referral_type === self::TYPE_PESO_OFFICE) {
            return ! empty($this->ref_or_no)
                && ! empty($this->ref_mayor_recipient_firstname)
                && ! empty($this->ref_mayor_recipient_lastname)
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

        return $hasProfile && $hasClearance && $this->hasRequiredDetails();
    }
}
