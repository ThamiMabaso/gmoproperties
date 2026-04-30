<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceIssuedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $invoiceId,
        public string $invoiceNumber,
        public string $companyName,
        public string $amountLabel,
    ) {
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable instanceof \App\Models\User && $notifiable->wantsEmailForCategory('invoices')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('tenant.invoices.show', $this->invoiceId);

        return (new MailMessage())
            ->subject('New invoice')
            ->line("{$this->companyName} issued invoice {$this->invoiceNumber} ({$this->amountLabel}).")
            ->action('View invoice', $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New invoice',
            'body' => "{$this->invoiceNumber} — {$this->amountLabel}",
            'action_label' => 'View',
            'action_url' => route('tenant.invoices.show', $this->invoiceId),
        ];
    }
}
