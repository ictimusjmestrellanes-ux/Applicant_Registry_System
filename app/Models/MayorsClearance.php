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
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
