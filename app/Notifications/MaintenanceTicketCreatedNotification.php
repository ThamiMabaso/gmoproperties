<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceTicketCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $companySlug,
        public int $ticketId,
        public string $ticketNumber,
        public string $title,
        public string $tenantName,
    ) {
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable instanceof \App\Models\User && $notifiable->wantsEmailForCategory('maintenance')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('company.maintenance.show', [
            'company' => $this->companySlug,
            'ticket' => $this->ticketId,
        ]);

        return (new MailMessage())
            ->subject('New maintenance ticket')
            ->line("{$this->tenantName} opened ticket {$this->ticketNumber}: {$this->title}.")
            ->action('View ticket', $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New maintenance ticket',
            'body' => "{$this->ticketNumber}: {$this->title}",
            'action_label' => 'View',
            'action_url' => route('company.maintenance.show', [
                'company' => $this->companySlug,
                'ticket' => $this->ticketId,
            ]),
        ];
    }
}
