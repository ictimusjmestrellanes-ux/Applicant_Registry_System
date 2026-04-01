<?php

use App\Http\Controllers\Api\ApplicantEducationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/educational-attainments', [ApplicantEducationController::class, 'index'])
        ->name('api.educational-attainments.index');
});
