<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'icon',
        'data',
        'action_url',
        'action_text',
        'expires_at',
        'is_global',
        'created_by'
    ];

    protected $casts = [
        'data' => 'array',
        'expires_at' => 'datetime',
        'is_global' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user who created this notification
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get users who have received this notification
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notification_users')
                    ->withPivot(['is_read', 'read_at', 'sent_at'])
                    ->withTimestamps();
    }

    /**
     * Get users who have read this notification
     */
    public function readUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('is_read', true);
    }

    /**
     * Get users who haven't read this notification
     */
    public function unreadUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('is_read', false);
    }

    /**
     * Scope to get active notifications (not expired)
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope to get expired notifications
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope to get global notifications
     */
    public function scopeGlobal(Builder $query): Builder
    {
        return $query->where('is_global', true);
    }

    /**
     * Scope to get notifications by type
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Check if notification is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if notification is active
     */
    public function isActive(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Send notification to specific users
     */
    public function sendToUsers(array $userIds): void
    {
        $userData = [];
        $now = now();

        foreach ($userIds as $userId) {
            $userData[] = [
                'notification_id' => $this->id,
                'user_id' => $userId,
                'is_read' => false,
                'sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        // Use insert to avoid duplicates and improve performance
        DB::table('notification_users')->insertOrIgnore($userData);
    }

    /**
     * Send notification to all users
     */
    public function sendToAllUsers(): void
    {
        $userIds = User::pluck('id')->toArray();
        $this->sendToUsers($userIds);
    }

    /**
     * Mark as read for a specific user
     */
    public function markAsReadForUser(int $userId): bool
    {
        return $this->users()
                    ->updateExistingPivot($userId, [
                        'is_read' => true,
                        'read_at' => now()
                    ]) > 0;
    }

    /**
     * Mark as unread for a specific user
     */
    public function markAsUnreadForUser(int $userId): bool
    {
        return $this->users()
                    ->updateExistingPivot($userId, [
                        'is_read' => false,
                        'read_at' => null
                    ]) > 0;
    }

    /**
     * Get notification with user read status
     */
    public static function getForUser(int $userId, int $limit = 50)
    {
        return static::active()
            ->with(['creator', 'users' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orWhere('is_global', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($notification) use ($userId) {
                $userPivot = $notification->users->where('id', $userId)->first();
                $notification->is_read = $userPivot ? $userPivot->pivot->is_read : false;
                $notification->read_at = $userPivot ? $userPivot->pivot->read_at : null;
                $notification->sent_at = $userPivot ? $userPivot->pivot->sent_at : $notification->created_at;
                unset($notification->users); // Clean up to avoid confusion
                return $notification;
            });
    }

    /**
     * Get unread count for user
     */
    public static function getUnreadCountForUser(int $userId): int
    {
        return static::active()
            ->where(function ($query) use ($userId) {
                $query->whereHas('users', function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->where('is_read', false);
                })
                ->orWhere(function ($q) use ($userId) {
                    $q->where('is_global', true)
                      ->whereDoesntHave('users', function ($subQ) use ($userId) {
                          $subQ->where('user_id', $userId);
                      });
                });
            })
            ->count();
    }
}