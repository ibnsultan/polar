<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get notifications for the authenticated user
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->input('limit', 50);
            $limit = min($limit, 100); // Maximum 100 notifications at once

            $notifications = Notification::getForUser(user()->id, $limit);
            $unreadCount = Notification::getUnreadCountForUser(user()->id);

            $this->notifications = $notifications;
            $this->unreadCount = $unreadCount;

            return $this->jsonSuccess('Notifications retrieved successfully');
        }

        catch (\Illuminate\Database\QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            $notification = Notification::findOrFail($id);
            
            // Check if user has access to this notification
            $hasAccess = $notification->is_global || 
                        $notification->users()->where('user_id', user()->id)->exists();
            
            if (!$hasAccess) {
                return $this->jsonError('Notification not found or access denied', 404);
            }

            $success = $notification->markAsReadForUser(user()->id);
            
            if (!$success) {
                return $this->jsonError('Failed to mark notification as read');
            }

            $this->updated = true;
            return $this->jsonSuccess('Notification marked as read');
        }

        catch (\Illuminate\Database\QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }

    /**
     * Mark a notification as unread
     */
    public function markAsUnread(Request $request, $id)
    {
        try {
            $notification = Notification::findOrFail($id);
            
            // Check if user has access to this notification
            $hasAccess = $notification->is_global || 
                        $notification->users()->where('user_id', user()->id)->exists();
            
            if (!$hasAccess) {
                return $this->jsonError('Notification not found or access denied', 404);
            }

            $success = $notification->markAsUnreadForUser(user()->id);
            
            if (!$success) {
                return $this->jsonError('Failed to mark notification as unread');
            }

            $this->updated = true;
            return $this->jsonSuccess('Notification marked as unread');
        }

        catch (\Illuminate\Database\QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }

    /**
     * Mark all notifications as read for the authenticated user
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $userId = user()->id;
            
            // Mark all user-specific notifications as read
            $userNotifications = Notification::active()
                ->whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                          ->where('is_read', false);
                });
            
            $updated = 0;
            foreach ($userNotifications->get() as $notification) {
                if ($notification->markAsReadForUser($userId)) {
                    $updated++;
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
                $updated++;
            }

            $this->updatedCount = $updated;
            return $this->jsonSuccess("Marked {$updated} notifications as read");
        }

        catch (\Illuminate\Database\QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead(){
        try{
            // Delete all read notifications for the user
            Notification::whereHas('users', function ($query) {
                $query->where('user_id', user()->id)
                      ->where('is_read', true);
            })->delete();

            return $this->jsonSuccess('All read notifications deleted successfully');
        }

        catch (\Illuminate\Database\QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        try {
            $count = Notification::getUnreadCountForUser(user()->id);
            
            $this->unreadCount = $count;
            return $this->jsonSuccess('Unread count retrieved successfully');
        }
        
        catch (\Illuminate\Database\QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }

    /**
     * Create a new notification (for authenticated users with permission)
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'type' => 'nullable|string|in:info,success,warning,error',
                'icon' => 'nullable|string|max:100',
                'action_url' => 'nullable|url|max:500',
                'action_text' => 'nullable|string|max:100',
                'expires_at' => 'nullable|date|after:now',
                'is_global' => 'boolean',
                'user_ids' => 'nullable|array',
                'user_ids.*' => 'integer|exists:users,id',
                'data' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return $this->jsonError($validator->errors()->first());
            }

            $notificationData = $request->only([
                'title', 'message', 'type', 'icon', 'action_url', 
                'action_text', 'expires_at', 'is_global', 'data'
            ]);
            
            $notificationData['created_by'] = user()->id;
            $notificationData['type'] = $notificationData['type'] ?? 'info';

            $notification = Notification::create($notificationData);

            // Send to specific users or all users if global
            if ($request->has('user_ids') && !empty($request->user_ids)) {
                $notification->sendToUsers($request->user_ids);
            } elseif ($notification->is_global) {
                $notification->sendToAllUsers();
            }

            $this->notification = $notification;
            return $this->jsonSuccess('Notification created and sent successfully');
        }

        catch (\Illuminate\Database\QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }

    /**
     * Send notification to specific users
     */
    public function sendToUsers(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_ids' => 'required|array|min:1',
                'user_ids.*' => 'integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return $this->jsonError($validator->errors()->first());
            }

            $notification = Notification::findOrFail($id);
            
            // Check if current user created this notification or has admin privileges
            if ($notification->created_by !== user()->id && !can('manage_notifications')) {
                return $this->jsonError('You do not have permission to send this notification', 403);
            }

            $notification->sendToUsers($request->user_ids);
            
            $this->sentCount = count($request->user_ids);
            return $this->jsonSuccess("Notification sent to {$this->sentCount} users");
        }

        catch (\Illuminate\Database\QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }

    /**
     * Get notification details
     */
    public function show($id)
    {
        try {
            $notification = Notification::with('creator')->findOrFail($id);
            
            // Check if user has access to this notification
            $hasAccess = $notification->is_global || 
                        $notification->users()->where('user_id', user()->id)->exists();
            
            if (!$hasAccess) {
                return $this->jsonError('Notification not found or access denied', 404);
            }

            // Get user's read status for this notification
            $userPivot = $notification->users()->where('user_id', user()->id)->first();
            $notification->is_read = $userPivot ? $userPivot->pivot->is_read : false;
            $notification->read_at = $userPivot ? $userPivot->pivot->read_at : null;

            $this->notification = $notification;
            return $this->jsonSuccess('Notification retrieved successfully');
        }

        catch (\Illuminate\Database\QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch (\Exception $e) {
            return $this->jsonException($e);
        }
    }

    public static function routes()
    {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::post('/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('delete-all-read');
        Route::post('/', [NotificationController::class, 'store'])->name('store');
        Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::patch('/{id}/unread', [NotificationController::class, 'markAsUnread'])->name('mark-unread');
        Route::post('/{id}/send', [NotificationController::class, 'sendToUsers'])->name('send-to-users');
    }
}
