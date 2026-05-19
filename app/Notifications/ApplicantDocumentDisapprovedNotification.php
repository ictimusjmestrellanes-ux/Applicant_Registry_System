<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicantDocumentDisapprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $applicantName,
        public int $applicantId,
        public string $documentLabel,
        public string $reason,
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
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Document disapproved',
            'message' => "{$this->documentLabel} for {$this->applicantName} was disapproved.",
            'reason' => $this->reason,
            'applicant_id' => $this->applicantId,
            'applicant_name' => $this->applicantName,
            'document_label' => $this->documentLabel,
            'url' => $this->sectionUrl,
        ];
    }
}
