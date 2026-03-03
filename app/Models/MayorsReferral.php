<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MayorsReferral extends Model
{
    protected $fillable = [
        'applicant_id',
        'resume',
        'barangay_clearance',
        'police_clearance',
        'nbi_clearance'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
