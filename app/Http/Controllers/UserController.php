<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Dashboard User Control
     * (Tahap 40%: tampilkan info user + shortcut menu)
     */
    public function dashboard()
    {
        $user = Auth::user();

        return view('dashboard', compact('user'));
    }

    /**
     * Read Profil (lihat data user)
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
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'email'));

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Deaktivasi Akun (CRUD: Delete)
     * Catatan: ini hard delete. Kalau mau soft delete, bilang—nanti saya ubah.
     */
    public function deactivateAccount(Request $request)
    {
        $user = Auth::user();

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Akun berhasil dinonaktifkan.');
    }
}