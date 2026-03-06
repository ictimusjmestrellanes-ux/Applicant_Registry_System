<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MayorsReferral extends Model
{
    protected $fillable = [
        'applicant_id',
        'resume',
        'ref_barangay_clearance',
        'ref_police_clearance',
        'ref_nbi_clearance'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
    
}
