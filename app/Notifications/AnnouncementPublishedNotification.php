<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnnouncementPublishedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int $announcementId,
        public string $companySlug,
        public string $title,
        public string $preview,
    ) {
    }

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable instanceof \App\Models\User && $notifiable->wantsEmailForCategory('announcements')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = $notifiable instanceof \App\Models\User && $notifiable->isTenant()
            ? route('tenant.announcements.show', $this->announcementId)
            : route('company.announcements.show', [
                'company' => $this->companySlug,
                'announcement' => $this->announcementId,
            ]);

        return (new MailMessage())
            ->subject('New announcement: ' . $this->title)
            ->line($this->preview)
            ->action('Read announcement', $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $url = $notifiable instanceof \App\Models\User && $notifiable->isTenant()
            ? route('tenant.announcements.show', $this->announcementId)
            : route('company.announcements.show', [
                'company' => $this->companySlug,
                'announcement' => $this->announcementId,
            ]);

        return [
            'title' => 'Announcement: ' . $this->title,
            'body' => $this->preview,
            'action_label' => 'Read',
            'action_url' => $url,
        ];
    }
}
