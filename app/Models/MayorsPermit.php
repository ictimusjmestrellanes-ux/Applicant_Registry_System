<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MayorsPermit extends Model
{
    protected $fillable = [

        'applicant_id',

        'health_card',
        'nbi_or_police_clearance',
        'cedula',
        'referral_letter',

        'permit_or_no',
        'peso_id_no',
        'community_tax_no',

        'permit_issued_on',

        'permit_date',
        'expires_on',

        'permit_doc_stamp_control_no',
        'permit_date_of_payment',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}