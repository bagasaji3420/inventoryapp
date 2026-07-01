<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Roles\PermissionController;
use App\Http\Controllers\Roles\RolesController;

//  Management Permission 
Route::resource('permissions', PermissionController::class)
    ->middleware('permission:permissions.read');

//  Management Roles 
Route::resource('roles', RolesController::class)
    ->middleware('permission:roles.read');
