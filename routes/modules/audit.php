<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Audit\AuditController;

Route::prefix('audit')->middleware('permission:logs.read')->group(function () {

    Route::get('/activity', [AuditController::class, 'index'])->name('activity-logs');
    Route::get('/activity/data', [AuditController::class, 'activityLogs'])->name('audit.log.activity');
});
