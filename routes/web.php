<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\DashboardController;

// Homepage
Route::redirect('/', '/login');

Route::view('/login', 'Admin.login')->name('login');



require __DIR__ . '/modules/auth.php';


// Backend
Route::middleware(['auth', 'user.status'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:dashboard.read');

    require __DIR__ . '/modules/users.php';
    require __DIR__ . '/modules/roles.php';
    require __DIR__ . '/modules/audit.php';
    require __DIR__ . '/modules/inventory.php';
    require __DIR__ . '/modules/settings.php';

    Route::get('/docs/flow', [\App\Http\Controllers\DocsController::class, 'flow'])
        ->name('docs.flow');

});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
