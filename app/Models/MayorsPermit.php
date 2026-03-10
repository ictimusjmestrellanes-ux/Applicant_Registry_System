<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MayorsPermit extends Model
{
    protected $table = 'mayors_permits';
    protected $fillable = [
        'applicant_id',
        'health_card',
        'nbi_or_police_clearance',
        'cedula',
        'referral_letter',

        'peso_id_no',
        'employers_name_or_address',
        'community_tax_no',
        'permit_issued_on',
        'permit_issued_in',
        'paid_under_official_receipt',
        'permit_date',
        'expires_on',
        'permit_doc_stamp_control_no',
        'permit_gor_serial_no',
        'permit_date_of_payment',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
    
}
