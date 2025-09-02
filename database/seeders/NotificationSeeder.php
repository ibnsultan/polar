<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user for testing
        $firstUser = User::first();
        
        if (!$firstUser) {
            $this->command->info('No users found. Please create a user first.');
            return;
        }

        // Create some sample notifications
        
        // 1. Welcome notification (Global)
        NotificationService::sendToAllUsers(
            'Welcome to Polar!',
            'Welcome to our platform! We\'re excited to have you here. Explore all the features we have to offer.',
            'success',
            'fas fa-star',
            null,
            null,
            ['category' => 'welcome'],
            null,
            $firstUser->id
        );

        // 2. System update notification (Global, with action)
        NotificationService::sendActionNotification(
            [$firstUser->id],
            'System Update Available',
            'A new system update is available with improved features and bug fixes.',
            route('dashboard'),
            'View Updates',
            'info',
            $firstUser->id
        );

        // 3. Warning notification
        NotificationService::sendWarning(
            [$firstUser->id],
            'Password Expiry Warning',
            'Your password will expire in 7 days. Please update your password to maintain account security.',
            $firstUser->id
        );

        // 4. Temporary notification (expires in 24 hours)
        NotificationService::sendTemporaryNotification(
            [$firstUser->id],
            'Limited Time Offer',
            'Special promotion available for the next 24 hours! Don\'t miss out on this exclusive deal.',
            24,
            'warning',
            $firstUser->id
        );

        // 5. Error notification
        NotificationService::sendError(
            [$firstUser->id],
            'Failed Login Attempt',
            'There was a failed login attempt to your account from an unknown device. If this wasn\'t you, please secure your account.',
            $firstUser->id
        );

        // 6. Success notification with action
        $notification = NotificationService::create(
            'Profile Completed',
            'Congratulations! Your profile has been successfully completed. You can now access all features.',
            'success',
            'fas fa-user-check',
            route('profile.show'),
            'View Profile',
            ['completion_percentage' => 100],
            null,
            false,
            $firstUser->id
        );
        $notification->sendToUsers([$firstUser->id]);

        // 7. Info notification about new features
        NotificationService::sendInfo(
            [$firstUser->id],
            'New Features Released',
            'We\'ve added exciting new features to improve your experience. Check out the latest updates in your dashboard.',
            $firstUser->id
        );

        $this->command->info('Sample notifications created successfully!');
    }
}
