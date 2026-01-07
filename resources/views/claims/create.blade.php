@extends('layouts.app')
@section('title', 'Isi Formulir Klaim')

@push('styles')
<style>
    /* Style Dasar (Seragam dengan Found Items) */
    body { 
        font-family: 'Poppins', sans-serif; 
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important; 
        min-height: 100vh; 
        color: white; 
    }
    
    /* Animasi Blob Background */
    .circle-bg { position: fixed; border-radius: 50%; background: rgba(255, 255, 255, 0.1); filter: blur(80px); z-index: -1; animation: float 8s ease-in-out infinite; }
    .c1 { width: 500px; height: 500px; top: -100px; left: -100px; background: #4facfe; opacity: 0.4; }
    .c2 { width: 400px; height: 400px; bottom: -50px; right: -50px; background: #43e97b; opacity: 0.3; animation-delay: 2s; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-30px); } }

    /* Glass Components */
    .glass-card { 
        background: rgba(255, 255, 255, 0.05); 
        backdrop-filter: blur(20px); 
        border: 1px solid rgba(255, 255, 255, 0.1); 
        border-radius: 20px; 
        padding: 30px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
    }
    
    .form-glass { 
        background: rgba(0, 0, 0, 0.3); 
        border: 1px solid rgba(255, 255, 255, 0.1); 
        color: white; 
        border-radius: 10px; 
        padding: 12px; 
    }
    .form-glass:focus { 
        background: rgba(0, 0, 0, 0.5); 
        border-color: #4fd1c5; 
        color: white; 
        box-shadow: none; 
    }
    
    label { color: #ccc; margin-bottom: 8px; font-size: 0.9rem; }
    
    /* Item Summary Box */
    .item-summary { 
        background: rgba(255,255,255,0.05); 
        border-radius: 12px; 
        padding: 20px; 
        border-left: 4px solid #4fd1c5; 
    }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Header --}}
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('claims.browse') }}" class="btn btn-outline-light rounded-pill me-3"><i class="fas fa-arrow-left"></i></a>
                <h2 class="fw-bold mb-0 text-white">Formulir Klaim Barang</h2>
            </div>

            <div class="glass-card">
                
                {{-- RINGKASAN BARANG (Dari Database Found Items) --}}
                <div class="item-summary mb-4">
                    <h5 class="fw-bold text-white mb-3">Anda akan mengklaim:</h5>
                    
                    <div class="d-flex align-items-start gap-3">
                        {{-- Foto Kecil --}}
                        <div class="rounded-3 overflow-hidden bg-white bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; flex-shrink: 0;">
                            @if(!empty($item->foto_barang))
                                <img src="{{ asset('storage/' . $item->foto_barang) }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <i class="fas fa-box text-white fs-2"></i>
                            @endif
                        </div>

                        <div>
                            <h4 class="fw-bold text-white mb-1">{{ $item->nama_barang }}</h4>
                            <div class="d-flex flex-wrap gap-3 text-white-50 small mb-2">
                                <span><i class="fas fa-tag me-1 text-info"></i> {{ $item->kategori }}</span>
                                {{-- Pastikan nama kolom di DB FoundItem adalah lokasi_ditemukan --}}
                                <span><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $item->lokasi_ditemukan ?? $item->lokasi ?? '-' }}</span>
                            </div>
                            <p class="text-white-50 m-0 small fst-italic">"{{ Str::limit($item->deskripsi, 100) }}"</p>
                        </div>
                    </div>
                </div>

                {{-- FORM INPUT --}}
                <form action="{{ route('claims.store') }}" method="POST">
                    @csrf
                    {{-- Hidden Input ID Barang --}}
                    <input type="hidden" name="item_id" value="{{ $item->id }}">

                    <div class="mb-4">
                        <label class="fw-bold">Alasan Klaim / Bukti Kepemilikan <span class="text-danger">*</span></label>
                        <textarea name="claim_reason" rows="4" class="form-control form-glass" 
                            placeholder="Jelaskan ciri-ciri khusus barang (isi dompet, wallpaper HP, stiker laptop, dll) untuk membuktikan barang ini milik Anda..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold">Nomor WhatsApp (Aktif) <span class="text-info small">(Opsional)</span></label>
                        <input type="text" name="phone" class="form-control form-glass" placeholder="Contoh: 08123456789">
                        <small class="text-white-50">Admin akan menghubungi nomor ini jika verifikasi berhasil.</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary fw-bold py-3 rounded-3" 
                                style="background: #4fd1c5; color: #0f2027; border: none; transition: 0.3s;">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan Klaim
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection