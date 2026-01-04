@extends('layouts.app')
@section('title', 'Unggah Temuan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* 1. BACKGROUND & GLOBAL */
    body { 
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important; 
        min-height: 100vh; 
        color: white; 
        font-family: 'Poppins', sans-serif;
    }
    .circle-bg { position: fixed; border-radius: 50%; filter: blur(80px); z-index: -1; animation: float 8s ease-in-out infinite; }
    .c1 { width: 500px; height: 500px; top: -100px; left: -100px; background: #4facfe; opacity: 0.4; }
    .c2 { width: 400px; height: 400px; bottom: -50px; right: -50px; background: #43e97b; opacity: 0.3; animation-delay: 2s; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-30px); } }

    /* 2. AREA FOTO (UPDATE) */
    .upload-zone {
        background: rgba(0, 0, 0, 0.3);
        border: 2px dashed rgba(79, 209, 197, 0.3);
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        transition: 0.3s;
    }
    .upload-zone:hover { border-color: #4fd1c5; background: rgba(0, 0, 0, 0.4); }

    /* 3. MAP STYLING */
    #map {
        height: 300px;
        width: 100%;
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 10px;
    }

    /* 4. FORM STYLING */
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 25px;
        padding: 30px;
    }
    .custom-input {
        background: rgba(0, 0, 0, 0.3) !important;
        border: 1px solid #2d3d3f !important;
        color: white !important;
        border-radius: 10px;
        padding: 12px;
    }
    .label-accent {
        color: #4fd1c5;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }
    .btn-submit {
        background: #000;
        color: white;
        font-weight: 900;
        border-radius: 15px;
        padding: 15px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: 0.3s;
    }
    .btn-submit:hover { background: #111; color: #4fd1c5; transform: translateY(-2px); }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('found-items.index') }}" class="btn btn-outline-light rounded-circle p-2" style="width: 40px; height: 40px;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h3 class="fw-bold m-0 uppercase italic">UNGGAH <span style="color: #4fd1c5;">TEMUAN</span></h3>
        <div style="width: 40px;"></div>
    </div>

    <form action="{{ route('found-items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="upload-zone mb-4">
            <div class="mb-3">
                <i class="fas fa-camera fa-2x text-teal-400 mb-2"></i>
                <h6 class="fw-bold">FOTO BARANG </h6>
                <img id="preview" src="#" alt="Preview" class="d-none mx-auto rounded-3 mb-3" style="max-height: 200px;">
            </div>
            <div class="d-flex justify-content-center">
                <input type="file" name="foto_barang" class="form-control custom-input w-50" id="imgInp" required>
            </div>
        </div>

        <div class="glass-card">
            <div class="row g-4">
                <div class="col-12 border-bottom border-white border-opacity-10 pb-2">
                    <h6 class="fw-bold m-0"><i class="fas fa-info-circle me-2 text-teal-400"></i>INFORMASI UTAMA</h6>
                </div>
                
                <div class="col-md-6">
                    <label class="label-accent">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control custom-input" placeholder="Input text..." required>
                </div>

                <div class="col-md-6">
                    <label class="label-accent">Kategori</label>
                    <select name="category" class="form-select custom-input" required>
                        <option value="">[ Dropdown Menu ]</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Dokumen">Dokumen</option>
                        <option value="Aksesoris">Aksesoris</option>
                    </select>
                </div>

                <div class="col-12 border-bottom border-white border-opacity-10 pb-2 mt-5">
                    <h6 class="fw-bold m-0"><i class="fas fa-map-marker-alt me-2 text-teal-400"></i>LOKASI & WAKTU</h6>
                </div>

                <div class="col-md-8">
                    <label class="label-accent">Set Lokasi Melalui Map</label>
                    <input type="hidden" name="koordinat_lokasi" id="koordinat_lokasi" required>
                    <div id="map"></div>
                    <small class="text-white-50 mt-2 d-block italic">Klik pada peta untuk menentukan titik koordinat penemuan.</small>
                </div>

                <div class="col-md-4">
                    <div class="mb-4">
                        <label class="label-accent">Alamat/Lokasi Spesifik</label>
                        <input type="text" name="lokasi_ditemukan" class="form-control custom-input" placeholder="Nama Gedung/Ruangan..." required>
                    </div>
                    <div>
                        <label class="label-accent">Tanggal Ditemukan</label>
                        <input type="date" name="tanggal_ditemukan" class="form-control custom-input" required>
                    </div>
                </div>

                <div class="col-12">
                    <label class="label-accent">Deskripsi Tambahan</label>
                    <textarea name="deskripsi" class="form-control custom-input" rows="3" placeholder="Sebutkan ciri khusus..."></textarea>
                </div>

                <div class="col-12 mt-5">
                    <button type="submit" class="btn btn-submit w-100 uppercase tracking-widest">
                        <i class="fas fa-upload me-2"></i> UNGGAH BARANG
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Preview Gambar
    imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
            preview.src = URL.createObjectURL(file)
            preview.classList.remove('d-none')
        }
    }

    // Inisialisasi Map (Default Bandung/Telkom University)
    var map = L.map('map').setView([-6.9744, 107.6303], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    // Klik Peta untuk Set Koordinat
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        document.getElementById('koordinat_lokasi').value = lat + "," + lng;
    });
</script>
@endpush