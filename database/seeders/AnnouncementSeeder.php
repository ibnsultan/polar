<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();
        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        $roles = \App\Models\Role::take(2)->pluck('id')->toArray();

        \App\Models\Announcement::create([
            'title' => 'Welcome to the Platform!',
            'content' => 'We are excited to have you on board. This announcement system will keep you updated with the latest news and updates.',
            'is_active' => true,
            'created_by' => $user->id,
            'target_roles' => null, // Show to all users
        ]);

        \App\Models\Announcement::create([
            'title' => 'New Feature: Advanced Analytics',
            'content' => 'We have released new advanced analytics features that will help you track your performance better.',
            'action_url' => 'https://example.com/analytics',
            'is_active' => true,
            'created_by' => $user->id,
            'target_roles' => $roles, // Show to specific roles
        ]);

        \App\Models\Announcement::create([
            'title' => 'Maintenance Scheduled',
            'content' => 'We will be performing scheduled maintenance on our servers this weekend. Expect minor downtime.',
            'is_active' => false, // Inactive announcement
            'created_by' => $user->id,
            'target_roles' => null,
        ]);

        $this->command->info('Announcements seeded successfully!');
    }
}
