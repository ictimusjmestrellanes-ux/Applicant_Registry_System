<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'ref_or_no',
        'ref_mayor_recipient_firstname',
        'ref_mayor_recipient_middlename',
        'ref_mayor_recipient_lastname',
        'ref_city_gov',
        'ref_company_address',
        'ref_hired_company',
        'ref_peso_or_no',
        'ref_recipient',
        'ref_place',
    ];

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

        return $hasProfile && $hasClearance;
    }
}
