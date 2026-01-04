<?php

namespace App\Http\Controllers;

use App\Models\FoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FoundItemController extends Controller
{
    public function index()
    {
        $items = FoundItem::where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('found_items.index', compact('items'));
    }

    public function create()
    {
        return view('found_items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'lokasi_ditemukan' => 'required|string|max:255',
            'tanggal_ditemukan' => 'required|date',
            'deskripsi' => 'required|string',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'koordinat_lokasi' => 'required|string', 
        ]);

        $pathFoto = null;
        if ($request->hasFile('foto_barang')) {
            $pathFoto = $request->file('foto_barang')->store('found_items', 'public');
        }

        FoundItem::create([
            'user_id' => Auth::id(),
            'nama_barang' => $request->nama_barang,
            'lokasi_ditemukan' => $request->lokasi_ditemukan,
            'tanggal_ditemukan' => $request->tanggal_ditemukan,
            'deskripsi' => $request->deskripsi,
            'foto_barang' => $pathFoto,
            'koordinat_lokasi' => $request->koordinat_lokasi,
            'status' => 'Unclaimed'
        ]);

        return redirect()->route('found-items.index')
            ->with('success', 'Barang temuan berhasil dilaporkan!');
    }

    public function result($id)
    {
        $item = FoundItem::where('user_id', Auth::id())->findOrFail($id);
        return view('found_items.result', compact('item'));
    }

    public function edit($id)
    {
        $item = FoundItem::where('user_id', Auth::id())->findOrFail($id);
        return view('found_items.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = FoundItem::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'lokasi_ditemukan' => 'required|string|max:255',
            'tanggal_ditemukan' => 'required|date',
            'deskripsi' => 'required|string',
            'status' => 'required|in:Unclaimed,Claimed,Closed',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto_barang')) {
            if ($item->foto_barang) {
                Storage::disk('public')->delete($item->foto_barang);
            }
            $item->foto_barang = $request->file('foto_barang')->store('found_items', 'public');
        }

        $item->update([
            'nama_barang' => $request->nama_barang,
            'lokasi_ditemukan' => $request->lokasi_ditemukan,
            'tanggal_ditemukan' => $request->tanggal_ditemukan,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()->route('found-items.index')
            ->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = FoundItem::where('user_id', Auth::id())->findOrFail($id);
        if ($item->foto_barang) {
            Storage::disk('public')->delete($item->foto_barang);
        }
        $item->delete();

        return redirect()->route('found-items.index')
            ->with('success', 'Data berhasil dihapus!');
    }
}