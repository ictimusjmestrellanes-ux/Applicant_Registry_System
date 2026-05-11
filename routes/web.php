<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\ApplicantPortalController;
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
Route::get('/auth/azure/redirect', [AuthController::class, 'redirectToAzure'])->name('login.azure.redirect');
Route::get('/auth/azure/callback', [AuthController::class, 'handleAzureCallback'])->name('login.azure.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/applicant/login', [ApplicantPortalController::class, 'showLoginForm'])->name('applicant.portal.login');
Route::post('/applicant/login', [ApplicantPortalController::class, 'login'])->name('applicant.portal.authenticate');
Route::get('/applicant/register', [ApplicantPortalController::class, 'showRegisterForm'])->name('applicant.portal.register');
Route::post('/applicant/register', [ApplicantPortalController::class, 'register'])->name('applicant.portal.register.store');
Route::get('/applicant', [ApplicantPortalController::class, 'index'])->name('applicant.portal.index');
Route::get('/applicant/personal-info', [ApplicantPortalController::class, 'personalInfo'])->name('applicant.portal.personal-info');
Route::post('/applicant/personal-info', [ApplicantPortalController::class, 'savePersonalInfo'])->name('applicant.portal.personal-info.store');
Route::get('/applicant/requirements', [ApplicantPortalController::class, 'requirements'])->name('applicant.portal.requirements');
Route::post('/applicant/profile', [ApplicantPortalController::class, 'update'])->name('applicant.portal.update');
Route::post('/applicant/requirements', [ApplicantPortalController::class, 'uploadRequirements'])->name('applicant.portal.requirements.store');
Route::post('/applicant/logout', [ApplicantPortalController::class, 'logout'])->name('applicant.portal.logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });

    Route::get('/reports', function () {
        return 'Reports page';
    })->name('reports.index');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');

    // ✅ MOVE THESE ABOVE RESOURCE
});

Route::get('applicants/archive', [ApplicantController::class, 'archive'])->middleware('auth')
    ->name('applicants.archive');

Route::get('applicants/export', [ApplicantController::class, 'export'])->middleware('auth')
    ->name('applicants.export');

Route::post('applicants/restore/{id}', [ApplicantController::class, 'restore'])->middleware('auth')
    ->name('applicants.restore');

Route::get('/applicants/{applicant}/view-file/{field}',
    [ApplicantController::class, 'viewFile']
)->middleware('auth')->name('applicants.view-file');

// ✅ PUT RESOURCE LAST
Route::resource('applicants', ApplicantController::class)->middleware('auth');
Route::put('/permits/{id}', [PermitController::class, 'update'])->middleware(['auth', 'permission:update_permit'])
    ->name('permits.update');
Route::put('/clearances/{id}', [ClearanceController::class, 'update'])->middleware(['auth', 'permission:update_clearance'])
    ->name('clearances.update');
Route::put('/referrals/{id}', [ReferralController::class, 'update'])->middleware(['auth', 'permission:update_referral'])
    ->name('referrals.update');
Route::get('/api/referrals/recipients', [ReferralController::class, 'searchRecipients'])->middleware('auth')
    ->name('referrals.recipients.search');

Route::get('/applicants/{id}/permit-id', [PermitController::class, 'printId'])->middleware(['auth', 'permission:generate_permit'])
    ->name('permits.printId');

Route::get('/applicants/{id}/print-clearance', [ClearanceController::class, 'printLetter'])->middleware(['auth', 'permission:generate_clearance'])
    ->name('clearances.printLetter');
    
Route::get('/applicants/{id}/print-referral', [ReferralController::class, 'printLetter'])->middleware(['auth', 'permission:generate_referral'])
    ->name('referrals.printLetter');
Route::get('/storage/view/{filename}', [App\Http\Controllers\StorageController::class, 'viewfile'])->middleware('auth')
    ->name('storage.view');
