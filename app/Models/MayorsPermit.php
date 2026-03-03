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
        'referral_letter'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
