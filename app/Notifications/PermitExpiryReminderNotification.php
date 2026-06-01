<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermitExpiryReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $applicantName,
        public string $expiresOn,
        public string $sectionUrl
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Mayor's Permit expiring soon")
            ->greeting("Hello {$this->applicantName},")
            ->line("Your Mayor's Permit to Work will expire on {$this->expiresOn}.")
            ->line('Please log in and check your permit details as soon as possible.')
            ->action('View Permit', $this->sectionUrl)
            ->line('This is an automated reminder from the applicant registry system.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => "Mayor's Permit expiring soon",
            'message' => "Your Mayor's Permit to Work will expire on {$this->expiresOn}.",
            'url' => $this->sectionUrl,
            'type' => 'permit_expiry_reminder',
            'expires_on' => $this->expiresOn,
        ];
    }
}
