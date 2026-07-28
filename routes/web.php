<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClearanceController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\RoleController;
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
        $record = $user?->isAdmin()
            ? \Illuminate\Support\Facades\DB::table('notifications')->where('id', $notification)->first()
            : $user?->notifications()->where('id', $notification)->firstOrFail();

        abort_if(! $record, 404);

        if ($user?->isAdmin()) {
            \Illuminate\Support\Facades\DB::table('notifications')
                ->where('id', $notification)
                ->update(['read_at' => now()]);
        } else {
            $record->markAsRead();
        }

        $recordData = is_string($record->data ?? null)
            ? (json_decode($record->data ?? '[]', true) ?: [])
            : ($record->data ?? []);

        $redirectUrl = data_get($recordData, 'url', route('dashboard'));

        return redirect()->to($redirectUrl);
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        $user = auth()->user();

        if ($user?->isAdmin()) {
            \Illuminate\Support\Facades\DB::table('notifications')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        } else {
            $user?->unreadNotifications()->update(['read_at' => now()]);
        }

        return back();
    })->name('notifications.read-all');

    Route::get('/users', [UserController::class, 'index'])
        ->middleware(['auth'])
        ->name('users.index');

    Route::middleware('admin')->group(function () {
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
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

Route::post('applicants/{applicant}/duplicate', [ApplicantController::class, 'duplicate'])
    ->middleware('auth')->name('applicants.duplicate');

Route::get('applicants/duplicates', [ApplicantController::class, 'duplicates'])
    ->middleware(['auth', 'permission:view_duplicates'])->name('applicants.duplicates');

Route::get('applicants/check-duplicates', [ApplicantController::class, 'checkDuplicates'])
    ->middleware('auth')->name('applicants.check-duplicates');

// ✅ PUT RESOURCE LAST
Route::resource('applicants', ApplicantController::class)->middleware('auth');
Route::put('/permits/{id}', [PermitController::class, 'update'])->middleware(['auth', 'permission:update_permit'])
    ->name('permits.update');
Route::put('/permits/{id}/approve', [PermitController::class, 'approve'])->middleware(['auth', 'permission:approve_document'])
    ->name('permits.approve');
Route::put('/permits/{id}/disapprove', [PermitController::class, 'disapprove'])->middleware(['auth', 'permission:approve_document'])
    ->name('permits.disapprove');
Route::put('/permits/{permit}/details', [PermitController::class, 'updateDetails'])->middleware(['auth', 'permission:update_permit'])
    ->name('permits.update-details');
Route::put('/permits/{id}/files', [PermitController::class, 'updateFiles'])->middleware(['auth', 'permission:update_permit'])
    ->name('permits.update-files');
Route::put('/clearances/{clearance}/details', [ClearanceController::class, 'updateDetails'])->middleware(['auth', 'permission:update_clearance'])
    ->name('clearances.update-details');
Route::put('/clearances/{id}/files', [ClearanceController::class, 'updateFiles'])->middleware(['auth', 'permission:update_clearance'])
    ->name('clearances.update-files');
Route::put('/referrals/{referral}/details', [ReferralController::class, 'updateDetails'])->middleware(['auth', 'permission:update_referral'])
    ->name('referrals.update-details');
Route::put('/referrals/{id}/files', [ReferralController::class, 'updateFiles'])->middleware(['auth', 'permission:update_referral'])
    ->name('referrals.update-files');
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

Route::get('/applicants/{id}/permit-id/{permit?}', [PermitController::class, 'printId'])->middleware(['auth', 'permission:generate_permit'])
    ->name('permits.printId');

Route::get('/applicants/{id}/print-clearance', [ClearanceController::class, 'printLetter'])->middleware(['auth', 'permission:generate_clearance'])
    ->name('clearances.printLetter');
    
Route::get('/applicants/{id}/print-referral', [ReferralController::class, 'printLetter'])->middleware(['auth'])
    ->name('referrals.printLetter');
Route::get('/storage/view/{filename}', [App\Http\Controllers\StorageController::class, 'viewfile'])
    ->middleware('auth')
    ->where('filename', '.*')
    ->name('storage.view');
