<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\FoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ClaimController extends Controller
{
    // 1. MENU UTAMA
    public function index()
    {
        return view('claims.index');
    }

    // 2. BROWSE BARANG (Hanya Tampilkan Unclaimed)
    public function browse(Request $request)
    {
        $query = FoundItem::where('status', 'Unclaimed'); 

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
                // Kategori dihapus sementara jika kolom tidak ada
            });
        }

        $items = $query->latest()->get();
        return view('claims.claim_barang', compact('items'));
    }

    // 3. FORM KLAIM
    public function create($item_id)
    {
        $item = FoundItem::findOrFail($item_id);
        return view('claims.create', compact('item'));
    }

    // 4. SIMPAN KLAIM
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'claim_reason' => 'required|string|max:1000',
            'phone' => 'nullable|string|max:20',
        ]);

        $sourceItem = FoundItem::findOrFail($request->item_id);

        $exists = Claim::where('user_id', Auth::id())
                       ->where('item_name', $sourceItem->nama_barang)
                       ->where('date_found', $sourceItem->tanggal_ditemukan)
                       ->exists();

        if ($exists) {
            return redirect()->route('claims.my-claims')->with('error', 'Anda sudah pernah mengajukan klaim untuk barang ini.');
        }

        $fullReason = $request->claim_reason . ($request->phone ? " (No. WA: {$request->phone})" : "");

        Claim::create([
            'user_id'        => Auth::id(),
            'item_name'      => $sourceItem->nama_barang,
            'category'       => $sourceItem->kategori,
            'location_found' => $sourceItem->lokasi_ditemukan, 
            'date_found'     => $sourceItem->tanggal_ditemukan,
            'description'    => $sourceItem->deskripsi,
            'status'         => 'pending',
            'claim_reason'   => $fullReason,
        ]);

        return redirect()->route('claims.my-claims')->with('success', 'Formulir klaim berhasil dikirim! Menunggu verifikasi.');
    }

    // 5. EDIT FORM
    public function edit($id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);

        if ($claim->status !== 'pending') {
            return redirect()->route('claims.my-claims')->with('error', 'Klaim yang sudah diproses tidak dapat diedit.');
        }

        return view('claims.edit', compact('claim'));
    }

    // 6. UPDATE DATA
    public function update(Request $request, $id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);

        if ($claim->status !== 'pending') {
            return back()->with('error', 'Gagal mengedit. Status sudah berubah.');
        }

        $request->validate([
            'claim_reason' => 'required|string|max:1000',
        ]);

        $claim->update([
            'claim_reason' => $request->claim_reason
        ]);

        return redirect()->route('claims.my-claims')->with('success', 'Data pengajuan klaim berhasil diperbarui.');
    }

    // 7. LIST KLAIM SAYA
    public function myClaims(Request $request)
{
    $query = Claim::where('user_id', Auth::id());

    if ($request->has('q') && $request->q != '') {
        $query->where('item_name', 'like', '%' . $request->q . '%');
    }

    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    $claims = $query->latest()->paginate(10);

    $claims->getCollection()->transform(function ($claim) {
        $claim->originalItem = FoundItem::where('nama_barang', $claim->item_name)
            ->where('tanggal_ditemukan', $claim->date_found)
            ->where('deskripsi', $claim->description)
            ->first();

        return $claim;
    });

    return view('claims.claims', compact('claims'));
}

    // 8. TANDAI SUDAH DIAMBIL (LOGIKA PENTING)
    public function markAsTaken($id)
    {
        // A. Update Status Klaim User
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);
        $claim->update(['status' => 'taken']);

        // B. Update Status Barang Asli di Database FoundItem
        // Supaya hilang dari list pencarian (Browse hanya menampilkan 'Unclaimed')
        $originalItem = FoundItem::where('nama_barang', $claim->item_name)
                                 ->where('tanggal_ditemukan', $claim->date_found)
                                 ->where('deskripsi', $claim->description)
                                 ->first();

        if ($originalItem) {
            $originalItem->update(['status' => 'Claimed']); 
        }

        return redirect()->route('claims.my-claims')->with('success', 'Barang berhasil dikonfirmasi telah diambil!');
    }

    // 9. BATALKAN
    public function destroy($id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);
        
        if ($claim->status == 'pending') {
            $claim->delete();
            return back()->with('success', 'Pengajuan klaim berhasil dibatalkan.');
        }

        return back()->with('error', 'Klaim yang sudah diproses tidak dapat dibatalkan.');
    }

    // 10. PRINT PDF
    public function printPdf($id)
    {
    $claim = Claim::where('user_id', Auth::id())->findOrFail($id);

    $originalItem = FoundItem::where('nama_barang', $claim->item_name)
        ->where('tanggal_ditemukan', $claim->date_found)
        ->where('deskripsi', $claim->description)
        ->first();

    $photoBase64 = null;

    try {
        if ($originalItem && $originalItem->foto_barang) {
            $photoPath = storage_path('app/public/' . $originalItem->foto_barang);

            if (is_file($photoPath) && is_readable($photoPath) && filesize($photoPath) <= 2 * 1024 * 1024) {
                $extension = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));

                $mime = match ($extension) {
                    'jpg', 'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    default => null,
                };

                if ($mime) {
                    $photoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoPath));
                }
            }
        }
    } catch (\Throwable $e) {
        $photoBase64 = null;
    }

    $pdf = Pdf::loadView('claims.pdf_single', compact('claim', 'originalItem', 'photoBase64'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream('Laporan-Klaim-' . $claim->id . '.pdf');
    }
}