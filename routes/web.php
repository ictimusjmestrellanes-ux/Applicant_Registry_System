<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClearanceController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\ReferralController;

// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Login/Logout routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/azure/redirect', [AuthController::class, 'redirectToAzure'])->name('login.azure.redirect');
Route::get('/auth/azure/callback', [AuthController::class, 'handleAzureCallback'])->name('login.azure.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', function () {
        return 'Users list';
    })->name('users.index');

    Route::get('/users/create', function () {
        return 'Add user form';
    })->name('users.create');

    Route::get('/reports', function () {
        return 'Reports page';
    })->name('reports.index');

    // ✅ MOVE THESE ABOVE RESOURCE
});

Route::get('applicants/archive', [ApplicantController::class, 'archive'])
    ->name('applicants.archive');

Route::post('applicants/restore/{id}', [ApplicantController::class, 'restore'])
    ->name('applicants.restore');

Route::get('/applicants/{applicant}/view-file/{field}',
    [ApplicantController::class, 'viewFile']
)->name('applicants.view-file');

// ✅ PUT RESOURCE LAST
Route::resource('applicants', ApplicantController::class);
Route::put('/permits/{id}', [PermitController::class,'update'])
    ->name('permits.update');
Route::put('/clearances/{id}', [ClearanceController::class,'update'])
    ->name('clearances.update');
Route::put('/referrals/{id}', [ReferralController::class,'update'])
    ->name('referrals.update');

    Route::post('/permits/{id}/generate-id', [PermitController::class, 'generateId'])
    ->name('permits.generateId');
