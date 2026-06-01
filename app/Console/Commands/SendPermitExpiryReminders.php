<?php

namespace App\Console\Commands;

use App\Models\MayorsPermit;
use App\Models\User;
use App\Notifications\PermitExpiryReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendPermitExpiryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permit:send-expiry-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for permits expiring within 15 days.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $permits = MayorsPermit::query()
            ->with(['applicant'])
            ->whereNotNull('expires_on')
            ->whereNull('expiry_reminder_sent_at')
            ->whereDate('expires_on', '>=', now()->startOfDay())
            ->whereDate('expires_on', '<=', now()->addDays(15)->startOfDay())
            ->get()
            ->filter(fn (MayorsPermit $permit) => $permit->needsExpiryReminder());

        $sent = 0;

        foreach ($permits as $permit) {
            $applicant = $permit->applicant;
            $email = trim((string) ($applicant?->email ?? ''));
            $applicantUser = User::query()->where('applicant_id', $permit->applicant_id)->first();

            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $applicantName = trim(implode(' ', array_filter([
                $applicant->first_name ?? '',
                $applicant->middle_name ?? '',
                $applicant->last_name ?? '',
                $applicant->suffix ?? '',
            ])));

            $notification = new PermitExpiryReminderNotification(
                $applicantName !== '' ? $applicantName : 'Applicant',
                (string) $permit->expires_on,
                route('applicants.edit', $permit->applicant_id).'#permit'
            );

            if ($applicantUser) {
                $applicantUser->notify($notification);
            }

            if (! $applicantUser || strcasecmp((string) $applicantUser->email, $email) !== 0) {
                Notification::route('mail', $email)->notify($notification);
            }

            $permit->forceFill([
                'expiry_reminder_sent_at' => now(),
            ])->saveQuietly();

            $sent++;
        }

        $this->info("Sent {$sent} permit expiry reminder email(s).");

        return self::SUCCESS;
    }
}
