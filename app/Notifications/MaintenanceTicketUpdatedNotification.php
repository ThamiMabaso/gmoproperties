<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceTicketUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $companySlug,
        public int $ticketId,
        public string $ticketNumber,
        public string $messageLine,
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
        $url = $notifiable instanceof \App\Models\User && $notifiable->isTenant()
            ? route('tenant.maintenance.show', $this->ticketId)
            : route('company.maintenance.show', [
                'company' => $this->companySlug,
                'ticket' => $this->ticketId,
            ]);

        return (new MailMessage())
            ->subject('Maintenance ticket update')
            ->line($this->messageLine)
            ->action('View ticket', $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Maintenance update',
            'body' => $this->messageLine,
            'action_label' => 'View',
            'action_url' => $notifiable instanceof \App\Models\User && $notifiable->isTenant()
                ? route('tenant.maintenance.show', $this->ticketId)
                : route('company.maintenance.show', [
                    'company' => $this->companySlug,
                    'ticket' => $this->ticketId,
                ]),
        ];
    }
}
