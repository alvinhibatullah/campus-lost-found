@extends('layouts.app')

@push('styles')
<style>
    /* 1. BACKGROUND GLOBAL */
    body { 
        background-color: #1a2a2d !important; 
        min-height: 100vh; 
        color: white; 
        font-family: 'Inter', sans-serif;
    }
    
    /* 2. GLASS FORM CONTAINER */
    .glass-form {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.4);
    }

    /* 3. INPUT STYLES (Teal Theme) */
    .form-control, .form-select {
        background: #142123 !important;
        border: 1px solid #2d3d3f !important;
        color: #ffffff !important;
        border-radius: 12px;
        padding: 14px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4fd1c5 !important;
        box-shadow: 0 0 15px rgba(79, 209, 197, 0.2) !important;
    }

    /* Custom Checkbox/Select Arrow */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%234fd1c5'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    /* 4. LABEL TITLES */
    .label-title {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #4fd1c5; /* Teal color */
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
        display: block;
        font-style: italic;
    }

    /* 5. BUTTON STYLES */
    .btn-update {
        background-color: #000000;
        color: white;
        font-weight: 900;
        letter-spacing: 2px;
        border-radius: 15px;
        border: 1px solid rgba(255,255,255,0.1);
        transition: all 0.3s;
    }

    .btn-update:hover {
        background-color: #111;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        color: #4fd1c5;
    }

    .back-link {
        color: rgba(255,255,255,0.5);
        font-size: 0.85rem;
        text-decoration: none;
        transition: 0.3s;
    }

    .back-link:hover { color: #4fd1c5; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            
            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('found-items.index') }}" class="back-link">
                    <i class="fas fa-chevron-left me-2"></i> KEMBALI KE RIWAYAT
                </a>
            </div>

            <div class="glass-form">
                <div class="text-center mb-5">
                    <h2 class="fw-black text-uppercase italic tracking-tighter" style="font-size: 2rem;">Edit <span class="text-teal-400" style="color: #4fd1c5;">Temuan</span></h2>
                    <div class="mx-auto bg-teal-500 mt-2" style="height: 3px; width: 50px; background-color: #4fd1c5;"></div>
                </div>

                <form action="{{ route('found-items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="label-title">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" value="{{ $item->nama_barang }}" required placeholder="Contoh: Dompet Hitam">
                        </div>

                        <div class="col-md-6">
                            <label class="label-title">Lokasi Ditemukan</label>
                            <input type="text" name="lokasi_ditemukan" class="form-control" value="{{ $item->lokasi_ditemukan }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="label-title">Tanggal Ditemukan</label>
                            <input type="date" name="tanggal_ditemukan" class="form-control" value="{{ $item->tanggal_ditemukan }}" required>
                        </div>

                        <div class="col-12">
                            <label class="label-title">Status Barang</label>
                            <select name="status" class="form-select fw-bold">
                                <option value="Unclaimed" {{ $item->status == 'Unclaimed' ? 'selected' : '' }}>Unclaimed (Belum Diambil)</option>
                                <option value="Claimed" {{ $item->status == 'Claimed' ? 'selected' : '' }}>Claimed (Sudah Diambil)</option>
                                <option value="Closed" {{ $item->status == 'Closed' ? 'selected' : '' }}>Closed (Selesai/Dibuang)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="label-title">Deskripsi Ciri-ciri</label>
                            <textarea name="deskripsi" class="form-control" rows="4" required>{{ $item->deskripsi }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="label-title">Update Foto Barang</label>
                            <div class="p-3 rounded-lg border border-dashed border-gray-600 bg-black bg-opacity-20">
                                <input type="file" name="foto_barang" class="form-control border-0 bg-transparent">
                                @if($item->foto_barang)
                                    <div class="mt-3 flex items-center gap-2">
                                        <i class="fas fa-check-circle text-teal-400"></i>
                                        <small class="text-gray-400 italic">Foto sudah ada. Unggah baru untuk mengganti.</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-update w-100 py-4 shadow-2xl">
                                SIMPAN PERUBAHAN DATA
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <p class="text-center mt-4 text-gray-600 text-[10px] uppercase tracking-widest">
                &copy; 2026 Campus Lost & Found - Arenko Section
            </p>

        </div>
    </div>
</div>
@endsection