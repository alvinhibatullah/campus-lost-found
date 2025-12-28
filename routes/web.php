<?php
use App\Http\Controllers\LostItemController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
    
});
Route::middleware(['auth'])->group(function () {
Route::resource('lost-items', LostItemController::class);
});


// --- HAPUS NANTI ---
Route::get('/bypass-login', function () {
    $user = User::firstOrCreate(
        ['email' => 'bayusamudera@test.com'], 
        [
            'name' => 'Bayu Samudera', 
            'password' => bcrypt('12345678')
        ]
    );
    Auth::login($user);
    return redirect()->route('lost-items.index');

});

Route::post('/logout', function () {
    Auth::logout(); // 
    return redirect('/bypass-login'); //
})->name('logout');