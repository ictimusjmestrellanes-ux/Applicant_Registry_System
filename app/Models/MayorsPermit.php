<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MayorsPermit extends Model
{
    protected $fillable = [

        'applicant_id',

        'health_card',
        'permit_nbi_clearance',
        'permit_police_clearance',
        'cedula',
        'referral_letter',

        'clearance_type',

        'permit_or_no',
        'peso_id_no',
        'community_tax_no',

        'permit_issued_on',
        'permit_issued_at',

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

    public static function generateNextPesoIdNo(?int $year = null): string
    {
        $year = $year ?? (int) now()->format('Y');
        $prefix = $year.'-';

        $latest = static::query()
            ->where('peso_id_no', 'like', $prefix.'%')
            ->orderByDesc('peso_id_no')
            ->value('peso_id_no');

        $nextNumber = 1;

        if ($latest && preg_match('/^\d{4}-(\d{5})$/', $latest, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        return $prefix.str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function isReadyForIdGeneration()
    {
        return
            // REQUIREMENTS
            ! empty($this->health_card) &&
            ! empty($this->cedula) &&
            (
                ! empty($this->permit_nbi_clearance) ||
                ! empty($this->permit_police_clearance)
            ) &&
            (
                stripos(optional($this->applicant)->city, 'IMUS CITY') !== false
                || ! empty($this->referral_letter)
            ) &&

            // DETAILS REQUIRED BEFORE ID NUMBER CAN BE GENERATED
            ! empty($this->permit_or_no) &&
            ! empty($this->community_tax_no) &&
            ! empty($this->permit_issued_on) &&
            ! empty($this->permit_issued_at) &&
            ! empty($this->permit_date) &&
            ! empty($this->expires_on) &&
            ! empty($this->permit_doc_stamp_control_no);
    }

    public function isComplete()
    {
        return
            $this->isReadyForIdGeneration() &&
            ! empty($this->peso_id_no) &&
            ! empty($this->permit_date_of_payment);
    }
}
