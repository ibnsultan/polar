<?php

namespace App\Http\Controllers\Base;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function fetch()
    {
        if (!user()) {
            return response()->json(['announcements' => []]);
        }

        $announcements = Announcement::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('target_roles')
                      ->orWhere(function($subQuery) {
                          $userRoleIds = user()->role->pluck('id')->toArray();
                          foreach ($userRoleIds as $roleId) {
                              $subQuery->orWhereJsonContains('target_roles', $roleId);
                          }
                      });
            })
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        return response()->json(['announcements' => $announcements]);
    }

    public static function routes()
    {
        Route::get('fetch', [self::class, 'fetch'])->name('announcements');
    }
}
