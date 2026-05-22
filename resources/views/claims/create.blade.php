@extends('layouts.app')
@section('title', 'Isi Formulir Klaim')

@push('styles')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important;
        min-height: 100vh;
        color: white;
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

    .form-glass::placeholder {
        color: rgba(255,255,255,0.5);
    }

    label {
        color: #ccc;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .item-summary {
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 20px;
        border-left: 4px solid #4fd1c5;
    }

    .item-photo {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        border-radius: 12px;
        overflow: hidden;
        background: rgba(255,255,255,0.1);
    }

    .item-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('claims.browse') }}" class="btn btn-outline-light rounded-pill me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0 text-white">Formulir Klaim Barang</h2>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 text-white mb-4" style="background: rgba(220, 53, 69, 0.7);">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="glass-card">

                <div class="item-summary mb-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="fw-bold text-white mb-0">Anda akan mengklaim:</h5>

                        @if(($item->source_type ?? 'found') === 'lost')
                            <span class="badge bg-warning text-dark rounded-pill">Lost Item</span>
                        @else
                            <span class="badge bg-info text-dark rounded-pill">Found Item</span>
                        @endif
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="item-photo d-flex align-items-center justify-content-center">
                            @if(!empty($item->foto_barang))
                                <img src="{{ asset('storage/' . $item->foto_barang) }}" alt="Foto Barang">
                            @else
                                <i class="fas fa-box text-white fs-2"></i>
                            @endif
                        </div>

                        <div>
                            <h4 class="fw-bold text-white mb-1">
                                {{ $item->nama_barang ?? '-' }}
                            </h4>

                            <div class="d-flex flex-wrap gap-3 text-white-50 small mb-2">
                                <span>
                                    <i class="fas fa-tag me-1 text-info"></i>
                                    {{ $item->display_kategori ?? $item->kategori ?? '-' }}
                                </span>

                                <span>
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                    {{ $item->display_lokasi ?? $item->lokasi_ditemukan ?? $item->koordinat_lokasi ?? '-' }}
                                </span>

                                <span>
                                    <i class="fas fa-calendar me-1 text-warning"></i>
                                    {{ $item->display_tanggal ?? $item->tanggal_ditemukan ?? $item->tanggal_hilang ?? '-' }}
                                </span>
                            </div>

                            <p class="text-white-50 m-0 small fst-italic">
                                "{{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 100) }}"
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('claims.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <input type="hidden" name="source_type" value="{{ $item->source_type ?? 'found' }}">

                    <div class="mb-4">
                        <label class="fw-bold">
                            Alasan Klaim / Bukti Kepemilikan <span class="text-danger">*</span>
                        </label>
                        <textarea name="claim_reason"
                                  rows="4"
                                  class="form-control form-glass"
                                  placeholder="Jelaskan ciri-ciri khusus barang untuk membuktikan barang ini milik Anda."
                                  required>{{ old('claim_reason') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold">
                            Nomor WhatsApp Aktif <span class="text-info small">(Opsional)</span>
                        </label>
                        <input type="text"
                               name="phone"
                               class="form-control form-glass"
                               value="{{ old('phone') }}"
                               placeholder="Contoh: 08123456789">
                        <small class="text-white-50">
                            Admin akan menghubungi nomor ini jika verifikasi berhasil.
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit"
                                class="btn btn-primary fw-bold py-3 rounded-3"
                                style="background: #4fd1c5; color: #0f2027; border: none;">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan Klaim
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection