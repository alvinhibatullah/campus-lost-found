<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLogProfile; // <--- 1. PENTING: Panggil Model Log

class UserController extends Controller
{
    /**
     * Dashboard User Control (Menu Awal)
     * Menampilkan profil singkat + shortcut + log aktivitas
     */
    public function home()
    {
        $user = Auth::user();

        // Ambil 5 aktivitas terakhir milik user ini, urutkan dari yang terbaru
        $logs = ActivityLogProfile::where('user_id', $user->id)
                                  ->latest()
                                  ->take(5)
                                  ->get();

        // Kirim data user & logs ke view 'home'
        return view('home', compact('user', 'logs'));
    }

    /**
     * Read Profil (lihat data user detail)
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Form Edit Profil
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('edit-profile', compact('user'));
    }

    /**
     * Update Profil (CRUD: Update)
     */
    public function updateProfile(Request $request)
    {
        // Validasi
        $request->validate([
            'name'      => 'required|string|max:255',
            'nim'       => 'nullable|string|max:20',
            'fakultas'  => 'nullable|string|max:100',
            'jurusan'   => 'nullable|string|max:100',
            'angkatan'  => 'nullable|numeric',
        ]);

        $user = Auth::user();
        
        // Simpan data profil
        $user->update($request->only('name', 'nim', 'fakultas', 'jurusan', 'angkatan'));

        // --- 2. PENTING: Catat Log Aktivitas ---
        ActivityLogProfile::create([
            'user_id'     => $user->id,
            'action'      => 'Update Profil',
            'description' => 'Anda telah memperbarui data profil.',
        ]);
        // ---------------------------------------

        // Arahkan kembali ke Menu Awal (home)
        return redirect()->route('home')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Deaktivasi Akun (CRUD: Delete)
     */
    public function deactivateAccount(Request $request)
    {
        $user = Auth::user();

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun berhasil dinonaktifkan.');
    }
}