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
}
