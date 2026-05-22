<!DOCTYPE html>
<html>
<head>
    <title>Result Laporan Klaim #{{ $claim->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 18px;
            letter-spacing: 1px;
            color: #1a202c;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #718096;
        }

        .divider {
            height: 3px;
            background-color: #4fd1c5;
            margin: 20px 0 30px 0;
            border-radius: 2px;
        }

        .content {
            margin: 0 10px;
        }

        .data-row {
            margin-bottom: 20px;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 8px;
        }

        .data-row:last-child {
            border-bottom: none;
        }

        .label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 4px;
        }

        .value {
            font-size: 15px;
            color: #2d3748;
            font-weight: 500;
        }

        .status-pending {
            color: #d69e2e;
        }

        .status-approved {
            color: #38a169;
        }

        .status-rejected {
            color: #e53e3e;
        }

        .status-taken {
            color: #38a169;
        }

        .photo-section {
            margin-top: 40px;
            text-align: center;
            page-break-inside: avoid;
        }

        .photo-box {
            width: 250px;
            height: 250px;
            background: #f7fafc;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            margin: 10px auto;
            text-align: center;
            overflow: hidden;
        }

        .photo-img {
            width: 220px;
            height: auto;
            margin-top: 10px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #a0aec0;
            border-top: 1px solid #edf2f7;
            padding-top: 20px;
        }
    </style>
</head>
<body>

@php
    $photoBase64 = null;

    if (isset($originalItem) && $originalItem->foto_barang) {
        $photoPath = storage_path('app/public/' . $originalItem->foto_barang);

        if (file_exists($photoPath)) {
            $mime = mime_content_type($photoPath);
            $photoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoPath));
        }
    }
@endphp

<div class="header">
    <h2>Result Laporan Klaim</h2>
    <p>Campus Lost & Found System • Modul Klaim</p>
</div>

<div class="divider"></div>

<div class="content">
    <div class="data-row">
        <span class="label">Nomor ID Klaim</span>
        <div class="value">#{{ $claim->id }}</div>
    </div>

    <div class="data-row">
        <span class="label">Nama Barang</span>
        <div class="value">{{ $claim->item_name ?? '-' }}</div>
    </div>

    <div class="data-row">
        <span class="label">Kategori</span>
        <div class="value">{{ $claim->category ?? '-' }}</div>
    </div>

    <div class="data-row">
        <span class="label">Lokasi Ditemukan</span>
        <div class="value">{{ $claim->location_found ?? '-' }}</div>
    </div>

    <div class="data-row">
        <span class="label">Tanggal Ditemukan</span>
        <div class="value">
            {{ $claim->date_found ? \Carbon\Carbon::parse($claim->date_found)->format('d F Y') : '-' }}
        </div>
    </div>

    <div class="data-row">
        <span class="label">Status Pengajuan</span>
        <div class="value status-{{ $claim->status }}">
            <strong>{{ ucfirst($claim->status ?? '-') }}</strong>
        </div>
    </div>

    <div class="data-row">
        <span class="label">Deskripsi Barang</span>
        <div class="value">{{ $claim->description ?? '-' }}</div>
    </div>

    <div class="data-row">
        <span class="label">Alasan Klaim / Bukti Kepemilikan</span>
        <div class="value" style="background: #f7fafc; padding: 10px; border-radius: 5px;">
            {{ $claim->claim_reason ?? '-' }}
        </div>
    </div>
</div>

<div class="photo-section">
    <span class="label">Lampiran Foto Barang</span>

    <div class="photo-box">
        @if(!empty($photoBase64))
            <img src="{!! $photoBase64 !!}" class="photo-img">
        @else
            <span style="color: #cbd5e0; font-size: 12px; line-height: 250px;">
                Foto tidak tersedia pada PDF
            </span>
        @endif
    </div>
</div>

<div class="footer">
    Dokumen ini digenerate secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i') }} WIB.
    <br>
    Harap bawa bukti fisik jika status disetujui.
</div>

</body>
</html>