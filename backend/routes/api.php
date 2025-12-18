<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Employer Routes
    Route::middleware(['role:employer'])->group(function () {
        Route::get('/my-jobs', [App\Http\Controllers\JobController::class, 'myJobs']);
        Route::post('/jobs', [App\Http\Controllers\JobController::class, 'store']);
        Route::put('/jobs/{job}', [App\Http\Controllers\JobController::class, 'update']);
        Route::delete('/jobs/{job}', [App\Http\Controllers\JobController::class, 'destroy']);
        Route::get('/jobs/{job}/applications', [App\Http\Controllers\ApplicationController::class, 'index']);
    });

    // Applicant Routes
    Route::middleware(['role:applicant'])->group(function () {
        Route::post('/applications', [App\Http\Controllers\ApplicationController::class, 'store']);
    });
});

Route::get('/jobs', [App\Http\Controllers\JobController::class, 'index']);
Route::get('/jobs/{job}', [App\Http\Controllers\JobController::class, 'show']);
