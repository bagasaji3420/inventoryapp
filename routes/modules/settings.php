<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SettingController;

Route::get('/settings/codes', [SettingController::class, 'codes'])
    ->name('settings.codes')
    ->middleware('permission:settings.read');

Route::put('/settings/codes', [SettingController::class, 'updateCodes'])
    ->name('settings.codes.update')
    ->middleware('permission:settings.update');
