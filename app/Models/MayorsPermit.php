<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MayorsPermit extends Model
{
    public const APPROVAL_PENDING = 'pending';

    public const APPROVAL_APPROVED = 'approved';

    public const APPROVAL_DISAPPROVED = 'disapproved';

    protected $fillable = [

        'applicant_id',
        'approval_status',
        'disapproval_reason',

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
        'penalty_applied',
        'penalty_applied_at',

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

    private static function pesoIdPrefix(int $year): string
    {
        return $year.'-';
    }

    public static function generateNextPesoIdNo(?int $year = null): string
    {
        $year = $year ?? (int) now()->format('Y');
        $prefix = static::pesoIdPrefix($year);

        $latestNumber = static::query()
            ->where('peso_id_no', 'like', $prefix.'%')
            ->pluck('peso_id_no')
            ->map(function ($pesoIdNo) use ($year) {
                if (! preg_match('/^'.preg_quote((string) $year, '/')."-(\d{5})$/", (string) $pesoIdNo, $matches)) {
                    return 0;
                }

                return (int) $matches[1];
            })
            ->max() ?: 0;

        $nextNumber = $latestNumber + 1;

        if ($nextNumber > 99999) {
            $nextNumber = 1;
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

    public function isApproved(): bool
    {
        return ($this->approval_status ?? self::APPROVAL_APPROVED) === self::APPROVAL_APPROVED;
    }

    public function hasSubmittedFiles(): bool
    {
        return ! empty($this->health_card)
            || ! empty($this->permit_nbi_clearance)
            || ! empty($this->permit_police_clearance)
            || ! empty($this->cedula)
            || ! empty($this->referral_letter);
    }

    public function approvalStatusLabel(): string
    {
        return ucfirst((string) ($this->approval_status ?: self::APPROVAL_APPROVED));
    }

    public function approvalStatusClass(): string
    {
        return match ($this->approval_status ?? self::APPROVAL_APPROVED) {
            self::APPROVAL_PENDING => 'text-bg-warning',
            self::APPROVAL_APPROVED => 'text-bg-success',
            self::APPROVAL_DISAPPROVED => 'text-bg-danger',
            default => 'text-bg-success',
        };
    }

    public function approvalStatusMessage(): string
    {
        return match ($this->approval_status ?? self::APPROVAL_APPROVED) {
            self::APPROVAL_PENDING => 'Awaiting admin or staff approval.',
            self::APPROVAL_DISAPPROVED => 'Disapproved by admin or staff.',
            default => 'Approved by admin or staff.',
        };
    }

    public function isComplete()
    {
        return
            $this->isApproved() &&
            $this->isReadyForIdGeneration() &&
            ! empty($this->peso_id_no) &&
            ! empty($this->permit_date_of_payment);
    }
}
