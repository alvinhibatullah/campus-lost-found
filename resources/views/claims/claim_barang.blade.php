@extends('layouts.app')
@section('title', 'Telusuri Barang')

@push('styles')
<style>
    /* GLOBAL STYLE (Persis Found Items) */
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important;
        min-height: 100vh;
        color: white;
        overflow-x: hidden;
    }
    
    /* Animasi Blob */
    .circle-bg {
        position: fixed; border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        filter: blur(80px); z-index: -1;
        animation: float 8s ease-in-out infinite;
    }
    .c1 { width: 500px; height: 500px; top: -100px; left: -100px; background: #4facfe; opacity: 0.4; }
    .c2 { width: 400px; height: 400px; bottom: -50px; right: -50px; background: #43e97b; opacity: 0.3; animation-delay: 2s; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-30px); } }

    /* GLASS CARD ITEM */
    .glass-card-item {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        transition: transform 0.3s ease, background 0.3s;
        height: 100%;
        display: flex; flex-direction: column;
    }
    .glass-card-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.1);
        border-color: #4fd1c5;
    }

    /* SEARCH INPUT */
    .search-glass {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white; border-radius: 50rem; padding: 12px 25px;
    }
    .search-glass:focus { background: rgba(0,0,0,0.5); border-color: #4fd1c5; color: white; box-shadow: none; }
    .search-glass::placeholder { color: rgba(255,255,255,0.4); }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container-fluid px-4 py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-white">Telusuri Barang</h2>
            <p class="text-white-50 m-0">Temukan barang hilang di sekitar kampus.</p>
        </div>
        
        <a href="{{ route('claims.my-claims') }}" class="btn btn-outline-light rounded-pill px-4">
            Lihat Klaim Saya <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>

    {{-- SEARCH BAR --}}
    <div class="row justify-content-center mb-5">
        <div class="col-lg-7">
            <form action="{{ route('claims.browse') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control search-glass" 
                           placeholder="Cari nama barang (Laptop, Kunci, dll)..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary px-4 rounded-pill ms-2 fw-bold" type="submit" style="background: #4fd1c5; border: none; color: #0f2027;">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- GRID ITEM (REAL DATA FROM DB) --}}
    <div class="row g-4">
        @forelse($items as $item)
        <div class="col-md-6 col-lg-4">
            <div class="glass-card-item p-4">
                
                {{-- Icon / Foto & Badge --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bg-white bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center overflow-hidden" style="width:60px; height:60px;">
                        @if(!empty($item->foto_barang))
                            <img src="{{ asset('storage/' . $item->foto_barang) }}" alt="Foto" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <i class="fas fa-box text-white fs-4"></i>
                        @endif
                    </div>
                    <span class="badge bg-white bg-opacity-10 fw-normal text-white px-3 py-2 rounded-pill">
                        {{ $item->kategori }}
                    </span>
                </div>

                <h4 class="fw-bold text-white mb-2">{{ $item->nama_barang }}</h4>
                
                <div class="d-flex flex-column gap-2 text-white-50 small mb-4">
                    <span><i class="fas fa-map-marker-alt me-2 text-danger"></i> {{ $item->lokasi_ditemukan }}</span>
                    <span><i class="fas fa-calendar-alt me-2 text-info"></i> {{ \Carbon\Carbon::parse($item->tanggal_ditemukan)->format('d M Y') }}</span>
                </div>

                <p class="text-white-50 small mb-4 flex-grow-1" style="line-height: 1.6;">
                    {{ Str::limit($item->deskripsi, 80) }}
                </p>

                {{-- TOMBOL MENUJU FORM --}}
                <a href="{{ route('claims.create', $item->id) }}" class="btn w-100 fw-bold py-2 rounded-3 text-decoration-none d-block text-center mt-auto" 
                   style="background: #4fd1c5; color: #0f2027; border: none;">
                    <i class="fas fa-hand-paper me-2"></i> Saya Pemiliknya
                </a>

            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-search fa-3x text-white-50 mb-3"></i>
            <p class="text-white-50 fs-5">Tidak ada barang temuan yang tersedia saat ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection