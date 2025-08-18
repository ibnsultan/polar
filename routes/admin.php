<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ModulesController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\PermissionsController;

Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::prefix('modules')->group(fn() => ModulesController::routes());
Route::prefix('roles')->group(fn() => RolesController::routes());
Route::prefix('permissions')->group(fn() => PermissionsController::routes());