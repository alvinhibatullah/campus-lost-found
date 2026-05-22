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
    $claim = Claim::where('user_id', Auth::id())->findOrFail($id);

    $originalItem = $this->findOriginalItem($claim);

    $photoBase64 = null;

    try {
        if ($originalItem && !empty($originalItem->foto_barang)) {
            $photoPath = storage_path('app/public/' . $originalItem->foto_barang);

            if (is_file($photoPath) && is_readable($photoPath) && filesize($photoPath) <= 2000000) {
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

    $tanggal = '-';

    try {
        if (!empty($claim->date_found)) {
            $tanggal = \Carbon\Carbon::parse($claim->date_found)->format('d F Y');
        }
    } catch (\Throwable $e) {
        $tanggal = $claim->date_found ?? '-';
    }

    $status = match ($claim->status) {
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'taken' => 'Selesai / Diambil',
        default => ucfirst($claim->status ?? '-'),
    };

    $photoHtml = $photoBase64
        ? '<img src="' . $photoBase64 . '" style="width:220px;height:auto;margin-top:10px;border-radius:8px;">'
        : '<div style="color:#a0aec0;font-size:12px;line-height:220px;">Foto tidak tersedia pada PDF</div>';

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                color: #2d3748;
                font-size: 13px;
                line-height: 1.5;
            }

            .header {
                text-align: center;
                margin-bottom: 25px;
            }

            .header h2 {
                margin: 0;
                font-size: 18px;
                text-transform: uppercase;
                color: #1a202c;
            }

            .header p {
                margin: 5px 0 0;
                color: #718096;
                font-size: 11px;
            }

            .divider {
                height: 3px;
                background: #4fd1c5;
                margin: 20px 0 25px 0;
            }

            .row {
                margin-bottom: 14px;
                border-bottom: 1px solid #edf2f7;
                padding-bottom: 8px;
            }

            .label {
                display: block;
                font-size: 10px;
                color: #718096;
                text-transform: uppercase;
                font-weight: bold;
                margin-bottom: 4px;
            }

            .value {
                font-size: 14px;
                color: #2d3748;
                font-weight: 500;
            }

            .reason {
                background: #f7fafc;
                padding: 10px;
                border-radius: 5px;
            }

            .photo-section {
                margin-top: 35px;
                text-align: center;
                page-break-inside: avoid;
            }

            .photo-box {
                width: 260px;
                min-height: 240px;
                margin: 10px auto;
                border: 2px dashed #cbd5e0;
                border-radius: 8px;
                background: #f7fafc;
                text-align: center;
            }

            .footer {
                margin-top: 40px;
                text-align: center;
                font-size: 10px;
                color: #a0aec0;
                border-top: 1px solid #edf2f7;
                padding-top: 15px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>Result Laporan Klaim</h2>
            <p>Campus Lost & Found System - Modul Klaim</p>
        </div>

        <div class="divider"></div>

        <div class="row">
            <span class="label">Nomor ID Klaim</span>
            <div class="value">#' . e($claim->id) . '</div>
        </div>

        <div class="row">
            <span class="label">Nama Barang</span>
            <div class="value">' . e($claim->item_name ?? '-') . '</div>
        </div>

        <div class="row">
            <span class="label">Kategori</span>
            <div class="value">' . e($claim->category ?? '-') . '</div>
        </div>

        <div class="row">
            <span class="label">Lokasi</span>
            <div class="value">' . e($claim->location_found ?? '-') . '</div>
        </div>

        <div class="row">
            <span class="label">Tanggal Barang</span>
            <div class="value">' . e($tanggal) . '</div>
        </div>

        <div class="row">
            <span class="label">Status Pengajuan</span>
            <div class="value">' . e($status) . '</div>
        </div>

        <div class="row">
            <span class="label">Deskripsi Barang</span>
            <div class="value">' . e($claim->description ?? '-') . '</div>
        </div>

        <div class="row">
            <span class="label">Alasan Klaim / Bukti Kepemilikan</span>
            <div class="value reason">' . e($claim->claim_reason ?? '-') . '</div>
        </div>

        <div class="photo-section">
            <span class="label">Lampiran Foto Barang</span>
            <div class="photo-box">
                ' . $photoHtml . '
            </div>
        </div>

        <div class="footer">
            Dokumen ini digenerate otomatis oleh sistem pada ' . now()->format('d/m/Y H:i') . ' WIB.
            <br>
            Harap bawa bukti fisik jika status disetujui.
        </div>
    </body>
    </html>
    ';

    return Pdf::loadHTML($html)
        ->setPaper('a4', 'portrait')
        ->stream('Laporan-Klaim-' . $claim->id . '.pdf');
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