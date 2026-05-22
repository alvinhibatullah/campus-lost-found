@extends('layouts.app')
@section('title', 'Telusuri Barang')

@push('styles')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important;
        min-height: 100vh;
        color: white;
        overflow-x: hidden;
    }

    .circle-bg {
        position: fixed;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        filter: blur(80px);
        z-index: -1;
        animation: float 8s ease-in-out infinite;
    }

    .c1 {
        width: 500px;
        height: 500px;
        top: -100px;
        left: -100px;
        background: #4facfe;
        opacity: 0.4;
    }

    .c2 {
        width: 400px;
        height: 400px;
        bottom: -50px;
        right: -50px;
        background: #43e97b;
        opacity: 0.3;
        animation-delay: 2s;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-30px);
        }
    }

    .glass-card-item {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        transition: transform 0.3s ease, background 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .glass-card-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.1);
        border-color: #4fd1c5;
    }

    .search-glass {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 50rem;
        padding: 12px 25px;
    }

    .search-glass:focus {
        background: rgba(0,0,0,0.5);
        border-color: #4fd1c5;
        color: white;
        box-shadow: none;
    }

    .search-glass::placeholder {
        color: rgba(255,255,255,0.4);
    }

    .item-photo {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        overflow: hidden;
        background: rgba(255,255,255,0.1);
        flex-shrink: 0;
    }

    .item-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .source-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 999px;
    }

    .source-found {
        background: rgba(56, 189, 248, 0.2);
        color: #38bdf8;
    }

    .source-lost {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
    }

    .btn-claim {
        background: #4fd1c5;
        color: #0f2027;
        border: none;
        transition: 0.3s;
    }

    .btn-claim:hover {
        background: #38bdb2;
        color: #0f2027;
    }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-white">Telusuri Barang</h2>
            <p class="text-white-50 m-0">Temukan barang hilang dan barang temuan di sekitar kampus.</p>
        </div>

        <a href="{{ route('claims.my-claims') }}" class="btn btn-outline-light rounded-pill px-4">
            Lihat Klaim Saya <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-lg-7">
            <form action="{{ route('claims.browse') }}" method="GET">
                <div class="input-group">
                    <input type="text"
                           name="search"
                           class="form-control search-glass"
                           placeholder="Cari nama barang (Laptop, Kunci, dll)..."
                           value="{{ request('search') }}">

                    <button class="btn btn-primary px-4 rounded-pill ms-2 fw-bold"
                            type="submit"
                            style="background: #4fd1c5; border: none; color: #0f2027;">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($items as $item)
            <div class="col-md-6 col-lg-4">
                <div class="glass-card-item p-4">

                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="item-photo d-flex align-items-center justify-content-center">
                            @if(!empty($item->foto_barang))
                                <img src="{{ asset('storage/' . $item->foto_barang) }}" alt="Foto Barang">
                            @else
                                <i class="fas fa-box text-white fs-4"></i>
                            @endif
                        </div>

                        @if(($item->source_type ?? 'found') === 'lost')
                            <span class="source-badge source-lost">Lost Item</span>
                        @else
                            <span class="source-badge source-found">Found Item</span>
                        @endif
                    </div>

                    <h4 class="fw-bold text-white mb-2">
                        {{ $item->nama_barang ?? '-' }}
                    </h4>

                    <div class="d-flex flex-column gap-2 text-white-50 small mb-4">
                        <span>
                            <i class="fas fa-tag me-2 text-info"></i>
                            {{ $item->display_kategori ?? $item->kategori ?? '-' }}
                        </span>

                        <span>
                            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                            {{ $item->display_lokasi ?? $item->lokasi_ditemukan ?? $item->koordinat_lokasi ?? '-' }}
                        </span>

                        <span>
                            <i class="fas fa-calendar-alt me-2 text-info"></i>
                            @if(!empty($item->display_tanggal))
                                {{ \Carbon\Carbon::parse($item->display_tanggal)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </span>
                    </div>

                    <p class="text-white-50 small mb-4 flex-grow-1" style="line-height: 1.6;">
                        {{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 80) }}
                    </p>

                    <a href="{{ route('claims.create', $item->id) }}?type={{ $item->source_type }}"
                       class="btn btn-claim w-100 fw-bold py-2 rounded-3 text-decoration-none d-block text-center mt-auto">
                        <i class="fas fa-hand-paper me-2"></i> Saya Pemiliknya
                    </a>

                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-search fa-3x text-white-50 mb-3"></i>
                <p class="text-white-50 fs-5">Tidak ada barang yang tersedia saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection