<?php

use App\Http\Controllers\Api\ApplicantEducationController;
use App\Http\Controllers\Api\PermitIssuedAtCityGovernmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/educational-attainments', [ApplicantEducationController::class, 'index'])
        ->name('api.educational-attainments.index');

    // Location APIs (provinces/cities/barangays) - no auth required for frontend usage
});

Route::get('/permit-issued-at/city-governments', [PermitIssuedAtCityGovernmentController::class, 'index'])
    ->name('api.permit-issued-at.city-governments');

// Provinces/cities/barangays routes removed — frontend now uses PSGC directly

// Local NCR (Metro Manila) city/municipality API
use App\Http\Controllers\Api\NcrController;

Route::get('/ncr/cities', [NcrController::class, 'cities'])->name('api.ncr.cities');
Route::get('/ncr/barangays', [NcrController::class, 'barangays'])->name('api.ncr.barangays');
