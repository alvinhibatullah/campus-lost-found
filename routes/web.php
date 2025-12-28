<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

// Login Google
Route::get('login/google', [LoginController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);

// Group Middleware Auth untuk dashboard dan CRUD profil
Route::middleware('auth')->group(function () {
    Route::get('/home', [UserController::class, 'dashboard'])->name('home');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/deactivate', [UserController::class, 'deactivateAccount'])->name('profile.deactivate');

    // Logout route
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});