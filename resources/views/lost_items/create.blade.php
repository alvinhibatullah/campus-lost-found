@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 300px; width: 100%; border-radius: 8px; z-index: 1; }
</style>
@endpush

@section('content')
<div class="mb-3">
    <a href="{{ route('lost-items.index') }}" class="text-decoration-none text-secondary">← Kembali</a>
</div>

<h3 class="mb-4 fw-bold">Pelaporan Barang Hilang</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('lost-items.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-7">
            <div class="card p-4 h-100 shadow-sm">
                <h5 class="card-title mb-3"><i class="fas fa-file-alt me-2 text-primary"></i>Detail Laporan</h5>
                
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Laptop HP Victus 16" value="{{ old('nama_barang') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('kategori_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Hilang</label>
                        <input type="date" name="tanggal_hilang" class="form-control" value="{{ old('tanggal_hilang') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Warna, ciri khusus, isi barang..." required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Barang (Opsional)</label>
                    <input type="file" name="foto_barang" class="form-control" accept="image/*">
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card p-4 h-100 shadow-sm">
                <h5 class="card-title mb-3"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Lokasi Terakhir</h5>
                <p class="text-muted small">Geser peta dan klik pada lokasi barang hilang.</p>
                
                <div id="map" class="mb-3 border"></div>
                
                <div class="mb-2">
                    <label class="form-label fw-bold">Koordinat Terpilih</label>
                    <input type="text" name="koordinat_lokasi" id="koordinat_lokasi" class="form-control bg-light" 
                           placeholder="Klik peta untuk isi otomatis..." 
                           value="{{ old('koordinat_lokasi') }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2 mt-4 mb-5">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-paper-plane me-2"></i> Kirim Laporan
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    // 1. Inisialisasi Peta (Default Lokasi: Telkom University Bandung - Ganti koordinat jika perlu)
    var map = L.map('map').setView([-6.9744, 107.6303], 15);

    // 2. Pasang Gambar Peta dari OpenStreetMap
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Variabel penampung marker (pin)
    var marker;

    // 3. Fungsi saat peta diklik
    function onMapClick(e) {
        // Hapus marker lama jika ada
        if (marker) {
            map.removeLayer(marker);
        }

        // Tambah marker baru di lokasi klik
        marker = L.marker(e.latlng).addTo(map);

        // Simpan koordinat ke input text
        // Format: Latitude, Longitude
        var coords = e.latlng.lat.toFixed(6) + ", " + e.latlng.lng.toFixed(6);
        document.getElementById('koordinat_lokasi').value = coords;
    }

    // Pasang event listener click
    map.on('click', onMapClick);
</script>
@endpush