<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Announcements\AnnouncementPublishService;
use Illuminate\Console\Command;

class PublishScheduledAnnouncements extends Command
{
    /**
     * @var string
     */
    protected $signature = 'announcements:publish-scheduled';

    /**
     * @var string
     */
    protected $description = 'Publish announcements whose publish_at time has passed';

    public function handle(AnnouncementPublishService $publishService): int
    {
        $count = $publishService->publishDueScheduled();
        $this->info('Published ' . $count . ' announcement(s).');

        return self::SUCCESS;
    }
}
