<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'contact_no',
        'address_line',
        'province',
        'city',
        'barangay',
    ];

    public function permit()
    {
        return $this->hasOne(MayorsPermit::class);
    }

    public function clearance()
    {
        return $this->hasOne(MayorsClearance::class);
    }

    public function referral()
    {
        return $this->hasOne(MayorsReferral::class);
    }

    public function isPermitComplete()
    {
        if (! $this->permit) {
            return false;
        }

        $permit = $this->permit;

        // Detect if resident of City of Imus
        $isImusResident = stripos($this->city, 'City of Imus') !== false;

        // Always required
        if (
            empty($permit->health_card) ||
            empty($permit->nbi_or_police_clearance) ||
            empty($permit->cedula)
        ) {
            return false;
        }

        // If NOT resident of Imus → referral required
        if (! $isImusResident && empty($permit->referral_letter)) {
            return false;
        }

        return true;
    }

    public function isClearanceComplete()
    {
        if (! $this->clearance) {
            return false;
        }

        $clearance = $this->clearance;

        return ! empty($clearance->prosecutor_clearance) &&
               ! empty($clearance->mtc_clearance) &&
               ! empty($clearance->rtc_clearance) &&
               ! empty($clearance->nbi_clearance) &&
               ! empty($clearance->barangay_clearance);
    }

    public function isReferralComplete()
    {
        if (! $this->referral) {
            return false;
        }

        // Requirement 1: Resume OR BioData
        $hasProfile = $this->referral->resume || $this->referral->biodata;

        // Requirement 2: At least ONE clearance
        $hasClearance =
            $this->referral->barangay_clearance ||
            $this->referral->police_clearance ||
            $this->referral->nbi_clearance;

        return $hasProfile && $hasClearance;
    }
}
