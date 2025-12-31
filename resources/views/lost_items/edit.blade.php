@extends('layouts.app')

@push('styles')
<style>
    /* 1. BACKGROUND GLOBAL (Sama dengan Index & Create) */
    body { background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important; min-height: 100vh; color: white; }
    
    /* Animasi Blob */
    .circle-bg { position: fixed; border-radius: 50%; filter: blur(80px); z-index: -1; animation: float 8s ease-in-out infinite; }
    .c1 { width: 500px; height: 500px; top: -100px; left: -100px; background: #4facfe; opacity: 0.4; }
    .c2 { width: 300px; height: 300px; top: 40%; right: 20%; background: #fa709a; opacity: 0.3; animation-delay: 2s; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-30px); } }

    /* 2. GLASS FORM CONTAINER */
    .glass-form {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        padding: 40px;
    }

    /* 3. INPUT STYLES - PERBAIKAN WARNA TEKS */
    .form-control, .form-select {
        background: rgba(0, 0, 0, 0.4); /* Gelap transparan */
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff !important; /* PAKSA TEKS PUTIH */
        border-radius: 12px;
        padding: 12px;
        font-weight: 500;
    }
    
    /* Efek saat diklik (Focus) */
    .form-control:focus, .form-select:focus {
        background: rgba(0, 0, 0, 0.6);
        border-color: #00d2ff;
        color: white !important;
        box-shadow: 0 0 15px rgba(0, 210, 255, 0.3);
    }

    /* Input Readonly (Koordinat) */
    .form-control[readonly] {
        background: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.6) !important;
        cursor: not-allowed;
        border-style: dashed;
    }

    /* Dropdown Options (Biar background hitam pas diklik) */
    option { background: #1a2a33; color: white; padding: 10px; }
    
    /* Warna Status Spesifik */
    option[value="Searching"] { color: #ffc107; font-weight: bold; }
    option[value="Found"] { color: #198754; font-weight: bold; }
    option[value="Closed"] { color: #adb5bd; }

    /* Judul Label Kecil */
    .label-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <a href="{{ route('lost-items.index') }}" class="text-white-50 text-decoration-none mb-4 d-inline-block hover-white">
                <i class="fas fa-arrow-left me-2"></i>Batal & Kembali
            </a>

            <div class="glass-form">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-white border-opacity-10 pb-3">
                    <h3 class="fw-bold m-0 text-white">Edit Laporan</h3>
                    <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25">
                        ID: #{{ $item->id }}
                    </span>
                </div>

                <form action="{{ route('lost-items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        
                        <div class="col-md-6">
                            <label class="label-title">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" value="{{ $item->nama_barang }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="label-title text-warning">Status Laporan</label>
                            <select name="status" class="form-select fw-bold">
                                <option value="Searching" {{ $item->status == 'Searching' ? 'selected' : '' }}>Searching (Mencari)</option>
                                <option value="Found" {{ $item->status == 'Found' ? 'selected' : '' }}>Found (Ditemukan)</option>
                                <option value="Closed" {{ $item->status == 'Closed' ? 'selected' : '' }}>Closed (Selesai/Batal)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="label-title">Kategori</label>
                            <select name="kategori_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="label-title">Tanggal Hilang</label>
                            <input type="date" name="tanggal_hilang" class="form-control" value="{{ $item->tanggal_hilang }}" required>
                        </div>

                        <div class="col-12">
                            <label class="label-title">Deskripsi Lengkap</label>
                            <textarea name="deskripsi" class="form-control" rows="4" required>{{ $item->deskripsi }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="label-title">Update Foto (Opsional)</label>
                            <input type="file" name="foto_barang" class="form-control mb-2">
                            @if($item->foto_barang)
                                <div class="d-flex align-items-center bg-dark bg-opacity-25 p-2 rounded">
                                    <i class="fas fa-image me-2 text-info"></i>
                                    <small class="text-white-50">Foto saat ini tersimpan.</small>
                                </div>
                            @endif
                        </div>

                        <div class="col-12">
                            <label class="label-title">Koordinat Lokasi (Tidak dapat diubah)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-white-50">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <input type="text" name="koordinat_lokasi" class="form-control font-monospace" value="{{ $item->koordinat_lokasi }}" readonly>
                            </div>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top border-white border-opacity-10">
                            <button type="submit" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-lg text-uppercase ls-1">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection