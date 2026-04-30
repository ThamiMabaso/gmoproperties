<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractSignatureUpdateNotification extends Notification
{
    public const VARIANT_COMPANY_SIGNED = 'company_signed';

    public const VARIANT_TENANT_SIGNED = 'tenant_signed';

    public const VARIANT_FULLY_ACTIVE = 'fully_active';

    use Queueable;

    public function __construct(
        public string $companySlug,
        public int $contractId,
        public string $contractNumber,
        public string $variant,
    ) {
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable instanceof \App\Models\User && $notifiable->wantsEmailForCategory('contracts')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = $this->portalUrl($notifiable);

        return (new MailMessage())
            ->subject('Contract update')
            ->line($this->summaryLine())
            ->action('Open contract', $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Contract update',
            'body' => $this->summaryLine(),
            'action_label' => 'Open',
            'action_url' => $this->portalUrl($notifiable),
        ];
    }

    private function summaryLine(): string
    {
        return match ($this->variant) {
            self::VARIANT_COMPANY_SIGNED => "Company signed contract {$this->contractNumber}.",
            self::VARIANT_TENANT_SIGNED => "Tenant signed contract {$this->contractNumber}.",
            self::VARIANT_FULLY_ACTIVE => "Contract {$this->contractNumber} is now active.",
            default => "Contract {$this->contractNumber} was updated.",
        };
    }

    private function portalUrl(object $notifiable): string
    {
        if ($notifiable instanceof \App\Models\User && $notifiable->isTenant()) {
            return route('tenant.contracts.show', $this->contractId);
        }

        return route('company.contracts.show', [
            'company' => $this->companySlug,
            'contract' => $this->contractId,
        ]);
    }
}
