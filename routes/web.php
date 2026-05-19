<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClearanceController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\UserController;

// Public entry point now routes to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login/Logout routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Applicant-specific login (uses applicant code / portal password)
Route::get('/applicant/login', [AuthController::class, 'showApplicantLoginForm'])->name('applicant.login');
// Route::post('/applicant/login', [AuthController::class, 'loginApplicant'])->name('applicant.login.post');
// Route::get('/applicant/register', [AuthController::class, 'showApplicantRegisterForm'])->name('applicant.register');
// Route::post('/applicant/register', [AuthController::class, 'registerApplicant'])->name('applicant.register.post');
Route::get('/auth/azure/redirect', [AuthController::class, 'redirectToAzure'])->name('login.azure.redirect');
Route::get('/auth/azure/callback', [AuthController::class, 'handleAzureCallback'])->name('login.azure.callback');
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('login.google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Applicant portal routes removed

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/notifications/{notification}/read', function (string $notification) {
        $user = auth()->user();
        $record = $user?->notifications()->where('id', $notification)->firstOrFail();
        $record->markAsRead();

        $redirectUrl = data_get($record->data, 'url', route('dashboard'));

        return redirect()->to($redirectUrl);
    })->name('notifications.read');

    Route::get('/users', [UserController::class, 'index'])
        ->middleware(['auth'])
        ->name('users.index');

    Route::middleware('admin')->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/reports', function () {
        return 'Reports page';
    })->name('reports.index');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');

    // ✅ MOVE THESE ABOVE RESOURCE
});

Route::get('applicants/archive', [ApplicantController::class, 'archive'])->middleware(['auth', 'permission:view_archive_applicants'])
    ->name('applicants.archive');

Route::get('applicants/export', [ApplicantController::class, 'export'])->middleware('auth')
    ->name('applicants.export');

Route::post('applicants/restore/{id}', [ApplicantController::class, 'restore'])->middleware(['auth', 'permission:restore_archive_applicants'])
    ->name('applicants.restore');

Route::get('/applicants/{applicant}/view-file/{field}',
    [ApplicantController::class, 'viewFile']
)->middleware('auth')->name('applicants.view-file');

// ✅ PUT RESOURCE LAST
Route::resource('applicants', ApplicantController::class)->middleware('auth');
Route::put('/permits/{id}', [PermitController::class, 'update'])->middleware(['auth', 'permission:update_permit'])
    ->name('permits.update');
Route::put('/permits/{id}/approve', [PermitController::class, 'approve'])->middleware(['auth', 'permission:approve_document'])
    ->name('permits.approve');
Route::put('/permits/{id}/disapprove', [PermitController::class, 'disapprove'])->middleware(['auth', 'permission:approve_document'])
    ->name('permits.disapprove');
Route::put('/clearances/{id}', [ClearanceController::class, 'update'])->middleware(['auth', 'permission:update_clearance'])
    ->name('clearances.update');
Route::put('/clearances/{id}/approve', [ClearanceController::class, 'approve'])->middleware(['auth', 'permission:approve_document'])
    ->name('clearances.approve');
Route::put('/clearances/{id}/disapprove', [ClearanceController::class, 'disapprove'])->middleware(['auth', 'permission:approve_document'])
    ->name('clearances.disapprove');
Route::put('/referrals/{id}', [ReferralController::class, 'update'])->middleware(['auth', 'permission:update_referral'])
    ->name('referrals.update');
Route::put('/referrals/{id}/approve', [ReferralController::class, 'approve'])->middleware(['auth', 'permission:approve_document'])
    ->name('referrals.approve');
Route::put('/referrals/{id}/disapprove', [ReferralController::class, 'disapprove'])->middleware(['auth', 'permission:approve_document'])
    ->name('referrals.disapprove');
Route::get('/api/referrals/recipients', [ReferralController::class, 'searchRecipients'])->middleware('auth')
    ->name('referrals.recipients.search');

Route::get('/applicants/{id}/permit-id', [PermitController::class, 'printId'])->middleware(['auth', 'permission:generate_permit'])
    ->name('permits.printId');

Route::get('/applicants/{id}/print-clearance', [ClearanceController::class, 'printLetter'])->middleware(['auth', 'permission:generate_clearance'])
    ->name('clearances.printLetter');
    
Route::get('/applicants/{id}/print-referral', [ReferralController::class, 'printLetter'])->middleware(['auth', 'permission:generate_referral'])
    ->name('referrals.printLetter');
Route::get('/storage/view/{filename}', [App\Http\Controllers\StorageController::class, 'viewfile'])
    ->middleware('auth')
    ->where('filename', '.*')
    ->name('storage.view');
