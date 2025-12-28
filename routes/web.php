<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\Admin\ClaimVerificationController;

Route::prefix('claims')->group(function () {
    Route::get('/', [ClaimController::class, 'index'])->name('claims.index');
    Route::get('/create', [ClaimController::class, 'create'])->name('claims.create');
    Route::post('/', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/{claim}', [ClaimController::class, 'show'])->name('claims.show');
});

Route::prefix('admin/claims')->group(function () {
    Route::get('/', [ClaimVerificationController::class, 'queue'])->name('admin.claims.queue');
    Route::post('/{claim}/verify', [ClaimVerificationController::class, 'verify'])->name('admin.claims.verify');
});
