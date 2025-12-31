<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\User;      
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;      
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;       

class LostItemController extends Controller
{
    public function index()
    {
        $items = LostItem::where('user_id', Auth::id())
                        ->with('category')
                        ->latest()
                        ->get();

        $usersData = User::select(DB::raw("COUNT(*) as count"), DB::raw("DATE(created_at) as date"))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $labels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d'); 
            $labels[] = Carbon::now()->subDays($i)->format('d M'); 
            
            $found = $usersData->firstWhere('date', $date);
            $chartData[] = $found ? $found->count : 0;
        }
                        
        return view('lost_items.index', compact('items', 'labels', 'chartData'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('lost_items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id', 
            'tanggal_hilang' => 'required|date',
            'deskripsi' => 'required|string',
            'koordinat_lokasi' => 'required',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $pathFoto = null;
        if ($request->hasFile('foto_barang')) {
            $pathFoto = $request->file('foto_barang')->store('lost_items', 'public');
        }
        try {
            LostItem::create([
                'user_id' => Auth::id(), 
                'category_id' => $request->kategori_id,
                'nama_barang' => $request->nama_barang,
                'deskripsi' => $request->deskripsi,
                'tanggal_hilang' => $request->tanggal_hilang,
                'koordinat_lokasi' => $request->koordinat_lokasi,
                'foto_barang' => $pathFoto,
                'status' => 'Searching',
            ]);
        } catch (\Exception $e) {
            dd("GAGAL MENYIMPAN KE DATABASE: ", $e->getMessage());
        }

        return redirect()->route('lost-items.index')->with('success', 'Laporan kehilangan berhasil dibuat!');
    }

    public function edit($id)
    {
        $item = LostItem::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::all();
        
        return view('lost_items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = LostItem::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'tanggal_hilang' => 'required|date',
            'deskripsi' => 'required|string',
            'koordinat_lokasi' => 'nullable|string',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Searching,Found,Closed'
        ]);

        if ($request->hasFile('foto_barang')) {
            if ($item->foto_barang) {
                Storage::disk('public')->delete($item->foto_barang);
            }
            $item->foto_barang = $request->file('foto_barang')->store('lost_items', 'public');
        }

        $item->update([
            'category_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'tanggal_hilang' => $request->tanggal_hilang,
            'koordinat_lokasi' => $request->koordinat_lokasi,
            'status' => $request->status,
        ]);

        return redirect()->route('lost-items.index')->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = LostItem::where('user_id', Auth::id())->findOrFail($id);
        
        if ($item->foto_barang) {
            Storage::disk('public')->delete($item->foto_barang);
        }
        
        $item->delete();

        return redirect()->route('lost-items.index')->with('success', 'Laporan berhasil dihapus.');
    }
}