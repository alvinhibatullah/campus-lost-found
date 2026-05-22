<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\FoundItem;
use App\Models\LostItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ClaimController extends Controller
{
    public function index()
    {
        return view('claims.index');
    }

    public function browse(Request $request)
{
    $search = $request->search;

    $foundItems = FoundItem::where('status', 'Unclaimed')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->get()
        ->map(function ($item) {
            $item->source_type = 'found';
            $item->display_lokasi = $item->lokasi_ditemukan ?? '-';
            $item->display_tanggal = $item->tanggal_ditemukan ?? null;
            $item->display_kategori = $item->kategori ?? '-';
            return $item;
        });

    $lostItems = LostItem::query()
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->get()
        ->map(function ($item) {
            $item->source_type = 'lost';
            $item->display_lokasi = $item->koordinat_lokasi ?? '-';
            $item->display_tanggal = $item->tanggal_hilang ?? null;

            $categoryName = '-';

            if (!empty($item->kategori_id)) {
                $category = \App\Models\Category::find($item->kategori_id);
                $categoryName = $category->nama ?? '-';
            } elseif (!empty($item->kategori)) {
                $categoryName = $item->kategori;
            }

            $item->display_kategori = $categoryName;

            return $item;
        });

    $items = $foundItems
        ->toBase()
        ->concat($lostItems)
        ->sortByDesc('created_at')
        ->values();

    return view('claims.claim_barang', compact('items'));
}

    public function create(Request $request, $item_id)
    {
        $sourceType = $request->query('type', 'found');

        if ($sourceType === 'lost') {
            $item = LostItem::findOrFail($item_id);

            $category = null;
            if (!empty($item->kategori_id)) {
                $category = Category::find($item->kategori_id);
            }

            $item->source_type = 'lost';
            $item->display_kategori = $category->nama ?? $item->kategori ?? '-';
            $item->display_lokasi = $item->koordinat_lokasi ?? '-';
            $item->display_tanggal = $item->tanggal_hilang ?? null;
        } else {
            $item = FoundItem::findOrFail($item_id);

            $item->source_type = 'found';
            $item->display_kategori = $item->kategori ?? '-';
            $item->display_lokasi = $item->lokasi_ditemukan ?? '-';
            $item->display_tanggal = $item->tanggal_ditemukan ?? null;
        }

        return view('claims.create', compact('item'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'source_type' => 'required|in:found,lost',
            'claim_reason' => 'required|string|max:1000',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($request->source_type === 'lost') {
            $sourceItem = LostItem::findOrFail($request->item_id);

            $category = null;
            if (!empty($sourceItem->kategori_id)) {
                $category = Category::find($sourceItem->kategori_id);
            }

            $itemName = $sourceItem->nama_barang;
            $categoryName = $category->nama ?? $sourceItem->kategori ?? '-';
            $location = $sourceItem->koordinat_lokasi ?? '-';
            $date = $sourceItem->tanggal_hilang;
            $description = $sourceItem->deskripsi;
        } else {
            $sourceItem = FoundItem::findOrFail($request->item_id);

            $itemName = $sourceItem->nama_barang;
            $categoryName = $sourceItem->kategori ?? '-';
            $location = $sourceItem->lokasi_ditemukan ?? '-';
            $date = $sourceItem->tanggal_ditemukan;
            $description = $sourceItem->deskripsi;
        }

        $exists = Claim::where('user_id', Auth::id())
            ->where('item_name', $itemName)
            ->where('date_found', $date)
            ->where('description', $description)
            ->exists();

        if ($exists) {
            return redirect()
                ->route('claims.my-claims')
                ->with('error', 'Anda sudah pernah mengajukan klaim untuk barang ini.');
        }

        $fullReason = $request->claim_reason;
        if ($request->phone) {
            $fullReason .= " (No. WA: {$request->phone})";
        }

        Claim::create([
            'user_id'        => Auth::id(),
            'item_name'      => $itemName,
            'category'       => $categoryName,
            'location_found' => $location,
            'date_found'     => $date,
            'description'    => $description,
            'status'         => 'pending',
            'claim_reason'   => $fullReason,
        ]);

        return redirect()
            ->route('claims.my-claims')
            ->with('success', 'Formulir klaim berhasil dikirim! Menunggu verifikasi.');
    }

    public function edit($id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);

        if ($claim->status !== 'pending') {
            return redirect()
                ->route('claims.my-claims')
                ->with('error', 'Klaim yang sudah diproses tidak dapat diedit.');
        }

        return view('claims.edit', compact('claim'));
    }

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
            'claim_reason' => $request->claim_reason,
        ]);

        return redirect()
            ->route('claims.my-claims')
            ->with('success', 'Data pengajuan klaim berhasil diperbarui.');
    }

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
            $claim->originalItem = $this->findOriginalItem($claim);
            return $claim;
        });

        return view('claims.claims', compact('claims'));
    }

    public function markAsTaken($id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);

        $claim->update([
            'status' => 'taken',
        ]);

        $foundItem = FoundItem::where('nama_barang', $claim->item_name)
            ->whereDate('tanggal_ditemukan', $claim->date_found)
            ->first();

        if ($foundItem) {
            $foundItem->update([
                'status' => 'Claimed',
            ]);
        }

        return redirect()
            ->route('claims.my-claims')
            ->with('success', 'Barang berhasil dikonfirmasi telah diambil!');
    }

    public function destroy($id)
    {
        $claim = Claim::where('user_id', Auth::id())->findOrFail($id);

        if ($claim->status === 'pending') {
            $claim->delete();

            return back()->with('success', 'Pengajuan klaim berhasil dibatalkan.');
        }

        return back()->with('error', 'Klaim yang sudah diproses tidak dapat dibatalkan.');
    }

    public function printPdf($id)
{
    return Pdf::loadHTML('<h1>PDF TEST BERHASIL</h1>')
        ->setPaper('a4', 'portrait')
        ->stream('test.pdf');
}

    private function findOriginalItem(Claim $claim)
    {
        $foundQuery = FoundItem::where('nama_barang', $claim->item_name);

        if (!empty($claim->date_found)) {
            $foundQuery->whereDate('tanggal_ditemukan', $claim->date_found);
        }

        $foundItem = $foundQuery->first();

        if ($foundItem) {
            return $foundItem;
        }

        $lostQuery = LostItem::where('nama_barang', $claim->item_name);

        if (!empty($claim->date_found)) {
            $lostQuery->whereDate('tanggal_hilang', $claim->date_found);
        }

        return $lostQuery->first();
    }
}