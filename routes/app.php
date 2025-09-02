<?php

/**
 * Application Routes
 *
 * All Routes defined here will be prefixed to /app/ and will
 * require user to be logged in
 * 
 */

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Base\NotificationController;
use App\Http\Controllers\Base\AnnouncementController;

Route::group(['prefix' => 'announcements'], fn() => AnnouncementController::routes());
Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], fn() => NotificationController::routes());