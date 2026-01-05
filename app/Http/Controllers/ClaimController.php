<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    /**
     * Menampilkan daftar claim dengan pagination.
     */
    public function index()
    {
        // PERBAIKAN: Menggunakan paginate(10) agar sesuai dengan view
        // latest() agar data terbaru muncul paling atas
        $claims = Claim::latest()->paginate(10);

        return view('claims.index', compact('claims'));
    }

    /**
     * Menampilkan form pembuatan claim baru.
     */
    public function create()
    {
        return view('claims.create');
    }

    /**
     * Menyimpan data claim baru ke database.
     */
    public function store(Request $request)
    {
        // 1. VALIDASI: Mencegah data kosong/salah masuk database
        // Sesuaikan nama field ('judul', 'deskripsi', dll) dengan input di form Anda
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'required|in:lost,found', // Contoh validasi pilihan
            'contact_info'=> 'required|string',
        ]);

        // 2. SIMPAN: Membuat record baru
        // Pastikan di model Claim.php sudah ada protected $fillable = ['title', ...];
        Claim::create($validatedData);

        // 3. REDIRECT: Kembali ke halaman index dengan pesan sukses
        return redirect()->route('claims.index')
                         ->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu claim spesifik.
     */
    public function show(Claim $claim)
    {
        return view('claims.show', compact('claim'));
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(Claim $claim)
    {
        return view('claims.edit', compact('claim'));
    }

    /**
     * Mengupdate data yang sudah ada.
     */
    public function update(Request $request, Claim $claim)
    {
        // Validasi ulang (mirip store, tapi kadang ada yang nullable)
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'required|in:lost,found',
            'contact_info'=> 'required|string',
        ]);

        // Update data
        $claim->update($validatedData);

        return redirect()->route('claims.index')
                         ->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Menghapus data.
     */
    public function destroy(Claim $claim)
    {
        $claim->delete();

        return redirect()->route('claims.index')
                         ->with('success', 'Data berhasil dihapus.');
    }
}