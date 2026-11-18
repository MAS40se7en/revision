<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\InterviewController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::resource('applications', ApplicationController::class);
    Route::resource('applications.interviews', InterviewController::class)->only(['store', 'destroy'])->shallow();
});

require __DIR__.'/settings.php';
