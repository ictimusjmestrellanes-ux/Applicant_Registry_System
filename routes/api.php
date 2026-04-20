<?php

use App\Http\Controllers\Api\ApplicantEducationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/educational-attainments', [ApplicantEducationController::class, 'index'])
        ->name('api.educational-attainments.index');

    // Location APIs (provinces/cities/barangays) - no auth required for frontend usage
});

// Provinces/cities/barangays routes removed — frontend now uses PSGC directly

