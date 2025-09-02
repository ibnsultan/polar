<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Create and send a notification to specific users
     */
    public static function sendToUsers(
        array $userIds,
        string $title,
        string $message,
        string $type = 'info',
        ?string $icon = null,
        ?string $actionUrl = null,
        ?string $actionText = null,
        ?array $data = null,
        ?Carbon $expiresAt = null,
        ?int $createdBy = null
    ): Notification {
        $notification = self::create(
            $title,
            $message,
            $type,
            $icon,
            $actionUrl,
            $actionText,
            $data,
            $expiresAt,
            false, // not global
            $createdBy
        );

        $notification->sendToUsers($userIds);
        
        return $notification;
    }

    /**
     * Create and send a global notification to all users
     */
    public static function sendToAllUsers(
        string $title,
        string $message,
        string $type = 'info',
        ?string $icon = null,
        ?string $actionUrl = null,
        ?string $actionText = null,
        ?array $data = null,
        ?Carbon $expiresAt = null,
        ?int $createdBy = null
    ): Notification {
        $notification = self::create(
            $title,
            $message,
            $type,
            $icon,
            $actionUrl,
            $actionText,
            $data,
            $expiresAt,
            true, // global
            $createdBy
        );

        $notification->sendToAllUsers();
        
        return $notification;
    }

    /**
     * Send a simple notification to a single user
     */
    public static function sendToUser(
        int $userId,
        string $title,
        string $message,
        string $type = 'info',
        ?int $createdBy = null
    ): Notification {
        return self::sendToUsers(
            [$userId],
            $title,
            $message,
            $type,
            null,
            null,
            null,
            null,
            null,
            $createdBy
        );
    }

    /**
     * Send a notification with action button to specific users
     */
    public static function sendActionNotification(
        array $userIds,
        string $title,
        string $message,
        string $actionUrl,
        string $actionText,
        string $type = 'info',
        ?int $createdBy = null
    ): Notification {
        return self::sendToUsers(
            $userIds,
            $title,
            $message,
            $type,
            null,
            $actionUrl,
            $actionText,
            null,
            null,
            $createdBy
        );
    }

    /**
     * Send a temporary notification that expires after a certain time
     */
    public static function sendTemporaryNotification(
        array $userIds,
        string $title,
        string $message,
        int $expiresInHours = 24,
        string $type = 'info',
        ?int $createdBy = null
    ): Notification {
        $expiresAt = now()->addHours($expiresInHours);
        
        return self::sendToUsers(
            $userIds,
            $title,
            $message,
            $type,
            null,
            null,
            null,
            null,
            $expiresAt,
            $createdBy
        );
    }

    /**
     * Create a notification without sending it
     */
    public static function create(
        string $title,
        string $message,
        string $type = 'info',
        ?string $icon = null,
        ?string $actionUrl = null,
        ?string $actionText = null,
        ?array $data = null,
        ?Carbon $expiresAt = null,
        bool $isGlobal = false,
        ?int $createdBy = null
    ): Notification {
        return Notification::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'action_url' => $actionUrl,
            'action_text' => $actionText,
            'data' => $data,
            'expires_at' => $expiresAt,
            'is_global' => $isGlobal,
            'created_by' => $createdBy
        ]);
    }

    /**
     * Get notifications for a specific user
     */
    public static function getUserNotifications(int $userId, int $limit = 50): \Illuminate\Support\Collection
    {
        return Notification::getForUser($userId, $limit);
    }

    /**
     * Get unread count for a specific user
     */
    public static function getUserUnreadCount(int $userId): int
    {
        return Notification::getUnreadCountForUser($userId);
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsReadForUser(int $userId): int
    {
        $count = 0;
        
        // Mark user-specific notifications as read
        $userNotifications = Notification::active()
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('is_read', false);
            });
        
        foreach ($userNotifications->get() as $notification) {
            if ($notification->markAsReadForUser($userId)) {
                $count++;
            }
        }

        // Handle global notifications that user hasn't interacted with
        $globalNotifications = Notification::active()
            ->global()
            ->whereDoesntHave('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        foreach ($globalNotifications as $notification) {
            // Create a read record for global notifications
            $notification->users()->attach($userId, [
                'is_read' => true,
                'read_at' => now(),
                'sent_at' => $notification->created_at
            ]);
            $count++;
        }

        return $count;
    }

    /**
     * Clean up expired notifications
     */
    public static function cleanupExpiredNotifications(): int
    {
        return Notification::expired()->delete();
    }

    // Common notification types with predefined styles

    /**
     * Send a success notification
     */
    public static function sendSuccess(
        array $userIds,
        string $title,
        string $message,
        ?int $createdBy = null
    ): Notification {
        return self::sendToUsers($userIds, $title, $message, 'success', 'fas fa-check-circle', null, null, null, null, $createdBy);
    }

    /**
     * Send an error notification
     */
    public static function sendError(
        array $userIds,
        string $title,
        string $message,
        ?int $createdBy = null
    ): Notification {
        return self::sendToUsers($userIds, $title, $message, 'error', 'fas fa-exclamation-triangle', null, null, null, null, $createdBy);
    }

    /**
     * Send a warning notification
     */
    public static function sendWarning(
        array $userIds,
        string $title,
        string $message,
        ?int $createdBy = null
    ): Notification {
        return self::sendToUsers($userIds, $title, $message, 'warning', 'fas fa-exclamation-circle', null, null, null, null, $createdBy);
    }

    /**
     * Send an info notification
     */
    public static function sendInfo(
        array $userIds,
        string $title,
        string $message,
        ?int $createdBy = null
    ): Notification {
        return self::sendToUsers($userIds, $title, $message, 'info', 'fas fa-info-circle', null, null, null, null, $createdBy);
    }
}
