<?php

declare(strict_types=1);

namespace App\Services\Announcements;

use App\Models\Announcement;
use App\Notifications\AnnouncementPublishedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

final class AnnouncementPublishService
{
    public function __construct(
        private readonly AnnouncementAudienceResolver $audienceResolver,
    ) {
    }

    /**
     * Mark announcement published and notify audience (idempotent per save).
     */
    public function publishNow(Announcement $announcement): void
    {
        if ($announcement->is_published) {
            return;
        }

        $announcement->loadMissing('company');

        $announcement->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        $recipients = $this->audienceResolver->notificationRecipients($announcement);

        $company = $announcement->company;

        if ($company === null) {
            return;
        }

        Notification::send(
            $recipients,
            new AnnouncementPublishedNotification(
                $announcement->id,
                (string) $company->slug,
                $announcement->title,
                Str::limit(strip_tags($announcement->body), 200),
            )
        );
    }

    /**
     * Process rows that should become visible based on schedule.
     */
    public function publishDueScheduled(): int
    {
        $due = Announcement::query()
            ->where('is_published', false)
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now())
            ->get();

        $count = 0;

        foreach ($due as $announcement) {
            $this->publishNow($announcement);
            $count++;
        }

        return $count;
    }
}
