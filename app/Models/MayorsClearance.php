<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MayorsClearance extends Model
{
    protected $fillable = [
        'applicant_id',
        'prosecutor_clearance',
        'mtc_clearance',
        'rtc_clearance',
        'nbi_clearance',
        'barangay_clearance',

        'clearance_or_no',
        'clearance_issued_on',
        'clearance_peso_control_no',
        'clearance_doc_stamp_control_no',
        'clearance_date_of_payment',
        'clearance_hired_company',

    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function isComplete()
    {
        return
            // REQUIREMENTS
            ! empty($this->prosecutor_clearance) &&
            ! empty($this->mtc_clearance) &&
            ! empty($this->rtc_clearance) &&
            ! empty($this->nbi_clearance) &&
            ! empty($this->barangay_clearance) &&

            ! empty($this->clearance_or_no) &&
            ! empty($this->clearance_issued_on) &&
            ! empty($this->clearance_peso_control_no) &&
            ! empty($this->clearance_doc_stamp_control_no) &&
            ! empty($this->clearance_date_of_payment) &&
            ! empty($this->clearance_hired_company);
    }
}
