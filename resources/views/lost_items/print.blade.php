<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehilangan #{{ $item->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #ccc; font-family: 'Times New Roman', serif; }
        .page {
            background: white; width: 21cm; min-height: 29.7cm; display: block;
            margin: 0 auto; margin-bottom: 0.5cm; box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
            padding: 2cm;
        }
        .header { border-bottom: 3px double black; padding-bottom: 10px; margin-bottom: 30px; text-align: center; }
        .foto-barang { max-width: 300px; max-height: 200px; border: 1px solid #ddd; padding: 5px; margin-top: 10px; }
        
        /* Tampilan saat mode cetak / PDF */
        @media print {
            body, .page { margin: 0; box-shadow: none; background: white; }
            .no-print { display: none !important; }
        }
        
    </style>
</head>
<body>

    <div class="text-center my-4 no-print">
        <button onclick="window.print()" class="btn btn-primary fw-bold px-4 rounded-pill">
            <i class="fas fa-print me-2"></i>Cetak / Simpan sebagai PDF
        </button>
        <a href="{{ route('lost-items.index') }}" class="btn btn-secondary rounded-pill px-4">Kembali</a>
    </div>

    <div class="page">
        <div class="header">
            <h3 class="fw-bold mb-0">CAMPUS LOST & FOUND</h3>
            <p class="mb-0">Pusat Layanan Barang Hilang & Temuan Mahasiswa</p>
            <small>Telkom University Bandung</small>
        </div>

        <h4 class="text-center fw-bold text-uppercase mb-4 text-decoration-underline">BERITA ACARA KEHILANGAN</h4>

        <p>Pada hari ini, <strong>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y') }}</strong>, telah dilaporkan kehilangan barang dengan rincian sebagai berikut:</p>

        <table class="table table-bordered mt-4">
            <tr>
                <th width="30%" class="bg-light">Nama Pelapor</th>
                <td>{{ Auth::user()->name }}</td>
            </tr>
            <tr>
                <th class="bg-light">Nama Barang</th>
                <td>{{ $item->nama_barang }}</td>
            </tr>
            <tr>
                <th class="bg-light">Kategori</th>
                <td>{{ $item->category->nama ?? '-' }}</td>
            </tr>
            <tr>
                <th class="bg-light">Tanggal Hilang</th>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_hilang)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <th class="bg-light">Lokasi / Koordinat</th>
                <td>{{ $item->koordinat_lokasi }}</td>
            </tr>
            <tr>
                <th class="bg-light">Status Saat Ini</th>
                <td>
                    <span class="badge border border-dark text-dark">
                        {{ $item->status }}
                    </span>
                </td>
            </tr>
            <tr>
                <th class="bg-light">Deskripsi / Ciri-ciri</th>
                <td>{{ $item->deskripsi }}</td>
            </tr>
        </table>

        @if($item->foto_barang)
        <div class="mt-4">
            <strong>Foto Barang:</strong><br>
            <img src="{{ asset('storage/' . $item->foto_barang) }}" class="foto-barang rounded">
        </div>
        @endif

        <div class="mt-5 d-flex justify-content-end">
            <div class="text-center" style="width: 200px;">
                <p class="mb-5">Pelapor,</p>
                <br>
                <p class="fw-bold text-decoration-underline">{{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>

</body>
</html>