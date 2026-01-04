@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    /* Styling Global */
    body { background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important; min-height: 100vh; color: white; }
    .circle-bg { position: fixed; border-radius: 50%; filter: blur(80px); z-index: -1; animation: float 8s ease-in-out infinite; }
    .c1 { width: 500px; height: 500px; top: -100px; left: -100px; background: #4facfe; opacity: 0.4; }
    .c2 { width: 400px; height: 400px; bottom: -50px; right: -50px; background: #43e97b; opacity: 0.3; animation-delay: 2s; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-30px); } }

    /* Glass Form */
    .glass-form {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        padding: 30px;
    }
    
    /* Input & Select Style */
    .form-control, .form-select {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white !important;
        border-radius: 12px;
        padding: 12px;
    }

    /* Placeholder Color Fix */
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6) !important;
        opacity: 1;
    }
    
    .form-control:read-only {
        background: rgba(0, 0, 0, 0.5);
        color: #00d2ff !important;
    }

    .form-control:focus, .form-select:focus {
        background: rgba(0, 0, 0, 0.5);
        border-color: #00d2ff;
        box-shadow: 0 0 15px rgba(0, 210, 255, 0.2);
        color: white;
    }
    
    /* --- PERBAIKAN WARNA DROPDOWN DI SINI --- */
    /* Ini memaksa background pilihan menjadi gelap pekat */
    .form-select option {
        background-color: #0f2027; /* Warna Hitam/Biru Gelap */
        color: white;
        padding: 10px;
    }
    
    #map { height: 350px; border-radius: 15px; border: 2px solid rgba(255,255,255,0.2); }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container py-4">
    <a href="{{ route('lost-items.index') }}" class="text-white-50 text-decoration-none mb-4 d-inline-block hover-white">
        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
    </a>

    <div class="row g-4">
        <div class="col-lg-12">
            <h3 class="fw-bold mb-1">Buat Laporan Baru</h3>
            <p class="text-white-50 mb-4">Isi form di bawah untuk melaporkan barang hilang.</p>
        </div>

       <div class="col-lg-8">
            
            @if ($errors->any())
                <div class="alert alert-danger border-0 text-white mb-4" style="background: rgba(220, 53, 69, 0.4);">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('lost-items.store') }}" method="POST" enctype="multipart/form-data" class="glass-form">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="small text-uppercase text-white-50 fw-bold ls-1 mb-2">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Jam Tangan Casio" required>
                    </div>

                    <div class="col-md-6">
                        <label class="small text-uppercase text-white-50 fw-bold ls-1 mb-2">Kategori</label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="" class="text-white-50">Pilih Kategori...</option>
                            
                            @if(isset($categories) && count($categories) > 0)
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>Data Kosong</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="small text-uppercase text-white-50 fw-bold ls-1 mb-2">Tanggal Hilang</label>
                        <input type="date" name="tanggal_hilang" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="small text-uppercase text-white-50 fw-bold ls-1 mb-2">Deskripsi Lengkap</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan Ciri Ciri Barang Disini..."></textarea>
                    </div>

                    <div class="col-12">
                        <label class="small text-uppercase text-white-50 fw-bold ls-1 mb-2">Foto (Opsional)</label>
                        <input type="file" name="foto_barang" class="form-control">
                    </div>
                </div>
        </div>

        <div class="col-lg-4">
            <div class="glass-form h-100 d-flex flex-column">
                <label class="small text-uppercase text-white-50 fw-bold ls-1 mb-3">
                    <i class="fas fa-map-marker-alt text-danger me-2"></i>Titik Lokasi
                </label>
                
                <div id="map" class="flex-grow-1 mb-3"></div>
                
                <label class="small text-white-50 mb-1">Koordinat Terpilih:</label>
                <input type="text" name="koordinat_lokasi" id="koordinat_lokasi" class="form-control text-center font-monospace mb-3" placeholder="Klik peta untuk ambil lokasi..." readonly>

                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg mt-auto">
                    <i class="fas fa-paper-plane me-2"></i> Kirim Laporan
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-6.9744, 107.6303], 15);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    var marker;
    map.on('click', function(e) {
        if (marker) map.removeLayer(marker);
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('koordinat_lokasi').value = e.latlng.lat.toFixed(6) + ", " + e.latlng.lng.toFixed(6);
    });
</script>
@endpush
@endsection