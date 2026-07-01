<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\User\UsersController;
use App\Http\Controllers\User\GoogleController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\SecurityController;
use App\Http\Controllers\User\NotificationController;
use Illuminate\Support\Facades\Auth;

//  Management User 
Route::get('/users/data', [UsersController::class, 'data'])->name('users.data');
Route::resource('users', UsersController::class)
    ->middleware('permission:users.read');

// Account Setting  
Route::get('/settings/account/{username}', [SettingsController::class, 'index'])->name('profile');
Route::post('/settings/account/update', [SettingsController::class, 'updateAccount'])->name('account.update');
Route::delete('/settings/account/delete', [SettingsController::class, 'destroy'])->name('account.delete');

// Security Setting  
Route::get('/settings/security', [SecurityController::class, 'index'])->name('security');
Route::delete('/settings/security/sessions/{id}', [SecurityController::class, 'destroySession'])
    ->name('session.destroy');
Route::post('/settings/security/change_password', [SecurityController::class, 'changePassword'])->name('changePassword');


Route::get('/settings/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications', [NotificationController::class, 'homeNotif'])->name('notif.index');

Route::delete('/settings/notifications/{id}', [NotificationController::class, 'destroy'])
    ->name('notifications.destroy');

Route::post('/notifications/read-all', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.readAll');

Route::post('/notifications/{id}/read', function ($id) {
    $notif = Auth::user()
        ->unreadNotifications
        ->where('id', $id)
        ->first();

    if ($notif) {
        $notif->markAsRead();
    }

    return response()->json(['success' => true]);
})->name('notifications.read');

Route::delete('/notifications/clear-all', [NotificationController::class, 'destroyAll'])
    ->name('notifications.destroyAll');

Route::get('/check-status', function () {
    return response()->json([
        'status' => Auth::user()->status
    ]);
});

