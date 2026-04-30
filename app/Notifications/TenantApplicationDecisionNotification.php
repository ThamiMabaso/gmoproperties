<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantApplicationDecisionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $applicationId,
        public string $status,
        public string $companyName,
        public string $unitLabel,
        public ?string $rejectionReason,
    ) {
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable instanceof \App\Models\User && $notifiable->wantsEmailForCategory('applications')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('tenant.applications.show', $this->applicationId);

        $mail = (new MailMessage())
            ->subject('Application update')
            ->line("Your application for {$this->unitLabel} at {$this->companyName} is now: {$this->status}.");

        if ($this->rejectionReason !== null && $this->rejectionReason !== '') {
            $mail->line('Reason: ' . $this->rejectionReason);
        }

        return $mail->action('View application', $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Application update',
            'body' => "Status: {$this->status} for {$this->unitLabel}.",
            'action_label' => 'View',
            'action_url' => route('tenant.applications.show', $this->applicationId),
        ];
    }
}
