<?php

use App\Http\Controllers\Admin\AccountPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ResourcePageController;
use App\Http\Controllers\Admin\SupportSmsPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::middleware('api.session')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard/settings', [AccountPageController::class, 'settings'])->name('admin.settings');
    Route::put('/dashboard/settings', [AccountPageController::class, 'updateSettings'])->name('admin.settings.update');

    Route::get('/dashboard/notifications', [AccountPageController::class, 'notifications'])->name('admin.notifications');
    Route::post('/dashboard/notifications/read-all', [AccountPageController::class, 'markAllRead'])->name('admin.notifications.read-all');
    Route::post('/dashboard/notifications/{id}/read', [AccountPageController::class, 'markRead'])->name('admin.notifications.read');

    Route::get('/dashboard/profile', [AccountPageController::class, 'profile'])->name('admin.profile');
    Route::put('/dashboard/profile', [AccountPageController::class, 'updateProfile'])->name('admin.profile.update');

    Route::get('/dashboard/support-sms', [SupportSmsPageController::class, 'index'])->name('admin.support-sms');
    Route::post('/dashboard/support-sms', [SupportSmsPageController::class, 'store'])->name('admin.support-sms.store');
    Route::post('/dashboard/support-sms/reply', [SupportSmsPageController::class, 'reply'])->name('admin.support-sms.reply');

    Route::get('/dashboard/{resource}', [ResourcePageController::class, 'index'])->name('admin.resources.index');
    Route::get('/dashboard/{resource}/create', [ResourcePageController::class, 'create'])->name('admin.resources.create');
    Route::post('/dashboard/{resource}', [ResourcePageController::class, 'store'])->name('admin.resources.store');
    Route::post('/dashboard/{resource}/{id}/cancel', [ResourcePageController::class, 'cancel'])->name('admin.resources.cancel');
    Route::get('/dashboard/{resource}/{id}', [ResourcePageController::class, 'show'])->name('admin.resources.show');
    Route::get('/dashboard/{resource}/{id}/edit', [ResourcePageController::class, 'edit'])->name('admin.resources.edit');
    Route::put('/dashboard/{resource}/{id}', [ResourcePageController::class, 'update'])->name('admin.resources.update');
    Route::delete('/dashboard/{resource}/{id}', [ResourcePageController::class, 'destroy'])->name('admin.resources.destroy');
});
