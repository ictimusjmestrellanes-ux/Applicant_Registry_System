<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MayorsClearance extends Model
{
    public const APPROVAL_PENDING = 'pending';

    public const APPROVAL_APPROVED = 'approved';

    public const APPROVAL_DISAPPROVED = 'disapproved';

    protected $fillable = [
        'applicant_id',
        'approval_status',
        'disapproval_reason',
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

    public static function generateNextPesoControlNo(?int $year = null): string
    {
        $year = $year ?? (int) now()->format('Y');
        $prefix = $year.'-';

        $latestNumber = static::query()
            ->where('clearance_peso_control_no', 'like', $prefix.'%')
            ->pluck('clearance_peso_control_no')
            ->map(function ($controlNo) use ($year) {
                if (! preg_match('/^'.preg_quote((string) $year, '/')."-(\d{4})$/", (string) $controlNo, $matches)) {
                    return 0;
                }

                return (int) $matches[1];
            })
            ->max() ?: 0;

        $nextNumber = $latestNumber + 1;

        if ($nextNumber > 9999) {
            $nextNumber = 1;
        }

        return $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function isApproved(): bool
    {
        return ($this->approval_status ?? self::APPROVAL_APPROVED) === self::APPROVAL_APPROVED;
    }

    public function approvalStatusLabel(): string
    {
        return ucfirst((string) ($this->approval_status ?: self::APPROVAL_APPROVED));
    }

    public function isComplete()
    {
        return
            $this->isApproved() &&
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

    public function isReadyForControlNumber(): bool
    {
        return
            ! empty($this->prosecutor_clearance) &&
            ! empty($this->mtc_clearance) &&
            ! empty($this->rtc_clearance) &&
            ! empty($this->nbi_clearance) &&
            ! empty($this->barangay_clearance) &&
            ! empty($this->clearance_or_no) &&
            ! empty($this->clearance_issued_on) &&
            ! empty($this->clearance_doc_stamp_control_no) &&
            ! empty($this->clearance_date_of_payment) &&
            ! empty($this->clearance_hired_company);
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
}
