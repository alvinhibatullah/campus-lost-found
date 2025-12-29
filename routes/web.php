<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Import Semua Controller Tim
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\FoundItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ClaimVerificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

// --- 1. PUBLIC ROUTES ---
Route::get('/', function () {
    return view('welcome');
    
});
Route::middleware(['auth'])->group(function () {
Route::resource('lost-items', LostItemController::class);
});

// Login Google (Alvin)
Route::get('login/google', [LoginController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);


// --- 2. PROTECTED ROUTES ---
Route::middleware(['auth'])->group(function () {
    
    // Fitur Profil & Dashboard (Alvin)
    Route::get('/home', [UserController::class, 'dashboard'])->name('home');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/deactivate', [UserController::class, 'deactivateAccount'])->name('profile.deactivate');

    // Fitur Fadhlan
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Fitur Bayu (Lost Items)
    Route::resource('lost-items', LostItemController::class);

    // Fitur Arenko (Found Items)
    Route::resource('found-items', FoundItemController::class);

    // Fitur Dawai (Claims)
    Route::prefix('claims')->group(function () {
        Route::get('/', [ClaimController::class, 'index'])->name('claims.index');
        Route::get('/create', [ClaimController::class, 'create'])->name('claims.create');
        Route::post('/', [ClaimController::class, 'store'])->name('claims.store');
        Route::get('/{claim}', [ClaimController::class, 'show'])->name('claims.show');
    });

    // Fitur Admin Verification (Dawai)
    Route::prefix('admin/claims')->group(function () {
        Route::get('/', [ClaimVerificationController::class, 'queue'])->name('admin.claims.queue');
        Route::post('/{claim}/verify', [ClaimVerificationController::class, 'verify'])->name('admin.claims.verify');
    });
    
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});


// --- 3. DEVELOPMENT ONLY ---
Route::get('/bypass-login', function () {
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'bayusamudera@test.com'], 
        [
            'name' => 'Bayu Samudera', 
            'password' => bcrypt('12345678'),
            'google_id' => 'dummy_google_id_12345' 
        ]
    );

    Auth::login($user);
    return redirect()->route('lost-items.index');
});
