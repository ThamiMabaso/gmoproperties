<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantApplicationSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $companySlug,
        public int $applicationId,
        public string $companyName,
        public string $unitLabel,
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
        $url = route('company.applications.show', [
            'company' => $this->companySlug,
            'application' => $this->applicationId,
        ]);

        return (new MailMessage())
            ->subject('New tenant application')
            ->line("A new application was submitted for {$this->unitLabel} at {$this->companyName}.")
            ->action('Review application', $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New tenant application',
            'body' => "Submitted for {$this->unitLabel} at {$this->companyName}.",
            'action_label' => 'Review',
            'action_url' => route('company.applications.show', [
                'company' => $this->companySlug,
                'application' => $this->applicationId,
            ]),
        ];
    }
}
