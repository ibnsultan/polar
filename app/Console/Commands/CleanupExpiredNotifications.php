<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

use App\Models\Notification;

class CleanupExpiredNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired notifications from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $count = Notification::expired()->count();
            $this->info("Dry run: Would delete {$count} expired notifications.");
            return 0;
        }

        $this->info('Cleaning up expired notifications...');
        
        $deletedCount = NotificationService::cleanupExpiredNotifications();
        
        if ($deletedCount > 0) {
            $this->info("Successfully deleted {$deletedCount} expired notifications.");
        } else {
            $this->info('No expired notifications found.');
        }

        return 0;
    }
}
