<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\MobileVerificationController;
use App\Http\Controllers\Candidate\ProfileController as CandidateProfileController;
use App\Http\Controllers\Auth\OtpLoginController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'mobile.verified'])
  ->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/verify-mobile', [MobileVerificationController::class, 'show'])
        ->name('mobile.verification.notice');

    Route::post('/verify-mobile/send', [MobileVerificationController::class, 'send'])
        ->name('mobile.verification.send');

    Route::post('/verify-mobile/verify', [MobileVerificationController::class, 'verify'])
        ->name('mobile.verification.verify');
Route::get('/candidate/profile', [CandidateProfileController::class, 'edit'])
    ->name('candidate.profile.edit');

Route::patch('/candidate/profile', [CandidateProfileController::class, 'update'])
    ->name('candidate.profile.update');
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
