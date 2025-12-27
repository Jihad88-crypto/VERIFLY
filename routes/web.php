<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/pricing', function () {
        return view('pages.pricing');
    })->name('pricing');

    Route::get('/support', function () {
        return view('pages.support');
    })->name('support');

    Route::get('/verify/video', function () {
        return view('pages.verify-video');
    })->name('verify.video');

    Route::get('/verify/image', function () {
        return view('pages.verify-image');
    })->name('verify.image');

    Route::get('/verify/audio', function () {
        return view('pages.verify-audio');
    })->name('verify.audio');

    Route::get('/developer/api', function () {
        return view('pages.api-dashboard');
    })->name('developer.api');

    Route::get('/activity/logs', function () {
        return view('pages.activity-logs');
    })->name('activity.logs');

    Route::get('/billing', function () {
        return view('pages.billing');
    })->name('billing');

    Route::get('/settings', function () {
        return view('pages.settings');
    })->name('settings');

    Route::controller(App\Http\Controllers\PageController::class)->group(function () {
        Route::get('/services', 'services')->name('services');
        Route::get('/developers', 'developers')->name('developers');
    });
});