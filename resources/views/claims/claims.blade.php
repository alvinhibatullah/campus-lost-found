@extends('layouts.app')
@section('title', 'Klaim Saya')

@push('styles')
<style>
    /* 1. BACKGROUND & GLOBAL */
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important;
        min-height: 100vh; color: white; overflow-x: hidden;
    }
    .circle-bg { position: fixed; border-radius: 50%; background: rgba(255, 255, 255, 0.1); filter: blur(80px); z-index: -1; animation: float 8s ease-in-out infinite; }
    .c1 { width: 500px; height: 500px; top: -100px; left: -100px; background: #4facfe; opacity: 0.4; }
    .c2 { width: 400px; height: 400px; bottom: -50px; right: -50px; background: #43e97b; opacity: 0.3; animation-delay: 2s; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-30px); } }

    /* 2. GLASS PANEL */
    .glass-section { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); padding: 20px; }

    /* 3. TABEL STATIS */
    .table-glass { width: 100%; border-collapse: separate; border-spacing: 0 10px; color: white; table-layout: fixed; }
    .table-glass th { text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; color: rgba(255,255,255,0.5); padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .table-glass tbody tr { background: rgba(0, 0, 0, 0.2); transition: 0.2s; }
    .table-glass tbody tr:hover { background: rgba(0, 0, 0, 0.4); transform: translateY(-2px); }
    .table-glass td { padding: 15px 10px; border: none; vertical-align: middle; word-wrap: break-word; }
    .table-glass td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
    .table-glass td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }

    /* Buttons */
    .btn-action { width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: none; color: white; margin-left: 3px; transition: 0.3s; }
    .btn-delete { background: rgba(255, 65, 108, 0.2); color: #ff416c; }
    .btn-delete:hover { background: #ff416c; color: white; }
    .btn-print { background: rgba(56, 189, 248, 0.2); color: #38bdf8; }
    .btn-print:hover { background: #38bdf8; color: #0f2027; }
    
    /* Form */
    .form-glass { background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
    .form-glass::placeholder { color: rgba(255, 255, 255, 0.5); }
    .form-glass:focus { background: rgba(255, 255, 255, 0.2); color: white; border-color: #4fd1c5; box-shadow: none; }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container-fluid px-4 py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-white">Klaim Saya</h2>
            <p class="text-white-50 m-0">Pantau status dan riwayat barang yang Anda klaim.</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('claims.index') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Menu
            </a>
            <a href="{{ route('claims.browse') }}" class="btn btn-primary rounded-pill px-4 shadow fw-bold" 
               style="background: #4fd1c5; border: none; color: #0f2027;">
                <i class="fas fa-plus me-2"></i> Klaim Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert glass-section py-3 text-white border-0 d-flex align-items-center mb-4">
        <i class="fas fa-check-circle text-success fs-4 me-3"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="glass-section mb-4 p-3">
        <form method="GET" action="{{ route('claims.my-claims') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="text-white-50 small mb-1">Pencarian</label>
                    <input type="text" name="q" class="form-control form-glass rounded-3" 
                           value="{{ request('q') }}" placeholder="Cari ID atau nama barang...">
                </div>
                <div class="col-md-3">
                    <label class="text-white-50 small mb-1">Status</label>
                    <select name="status" class="form-select form-glass rounded-3">
                        <option value="" class="text-dark">Semua Status</option>
                        <option value="pending" class="text-dark" {{ request('status')=='pending'?'selected':'' }}>Menunggu</option>
                        <option value="approved" class="text-dark" {{ request('status')=='approved'?'selected':'' }}>Disetujui</option>
                        <option value="rejected" class="text-dark" {{ request('status')=='rejected'?'selected':'' }}>Ditolak</option>
                        <option value="taken" class="text-dark" {{ request('status')=='taken'?'selected':'' }}>Selesai / Diambil</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-light w-100 fw-bold" style="border-radius: 8px;">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <div class="glass-section px-3 py-3">
        @if($claims->count() > 0)
        <table class="table-glass">
            <thead>
                <tr>
                    <th width="35%">Barang</th>
                    <th width="20%">Kategori</th>
                    <th width="20%">Tanggal Klaim</th>
                    <th width="15%">Status</th>
                    <th width="10%" class="text-end">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($claims as $claim)
                    @php
                        $badgeClass = match($claim->status) {
                            'pending'   => 'bg-warning text-dark',
                            'approved'  => 'bg-info text-dark',
                            'rejected'  => 'bg-danger',
                            'taken'     => 'bg-success', // Hijau untuk Taken
                            default     => 'bg-secondary'
                        };
                        $label = match($claim->status) {
                            'pending'   => 'Menunggu',
                            'approved'  => 'Disetujui',
                            'rejected'  => 'Ditolak',
                            'taken'     => 'Selesai / Diambil',
                            default     => ucfirst($claim->status)
                        };
                    @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-10 rounded-3 p-1 me-2 d-flex align-items-center justify-content-center" style="width:45px;height:45px; overflow:hidden;">
                                    @if (!empty($claim->originalItem) && $claim->originalItem->foto_barang)
                                        <img src="{{ asset('storage/' . $claim->originalItem->foto_barang) }}"
                                            alt="Foto Barang"
                                            style="width:45px; height:45px; object-fit:cover; border-radius:8px;">
                                    @else
                                        <i class="fas fa-box text-white-50"></i>
                                    @endif
                                </div>
                            <div style="line-height: 1.1;">
                                <div class="fw-bold text-white small">{{ $claim->item_name }}</div>
                                <small class="text-white-50" style="font-size: 0.7rem;">
                                    <i class="fas fa-map-marker-alt me-1 text-info"></i>{{ $claim->location_found ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-white bg-opacity-10 fw-normal" style="font-size: 0.7rem;">{{ $claim->category }}</span>
                    </td>
                    <td class="small text-white-50">
                        {{ $claim->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <span class="badge {{ $badgeClass }} rounded-pill" style="font-size: 0.7rem;">{{ $label }}</span>
                    </td>
                    
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            
                            {{-- 1. TOMBOL PRINT PDF --}}
                            <a href="{{ route('claims.print', $claim->id) }}" target="_blank" class="btn-action btn-print" title="Cetak Result PDF">
                                <i class="fas fa-print fa-xs"></i>
                            </a>
                            
                            {{-- 2. TOMBOL KONFIRMASI (CHECK) - Jika belum diambil --}}
                            @if($claim->status !== 'taken')
                                <form action="{{ route('claims.mark-taken', $claim->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Barang sudah Anda terima? Barang akan dihapus dari daftar temuan publik.');">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action" style="background: rgba(52, 211, 153, 0.2); color: #34d399;" title="Konfirmasi Sudah Diambil">
                                        <i class="fas fa-check fa-xs"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- 3. EDIT & DELETE (Hanya jika Pending) --}}
                            @if($claim->status === 'pending')
                                <a href="{{ route('claims.edit', $claim->id) }}" class="btn-action" style="background: rgba(251, 191, 36, 0.2); color: #fbbf24;" title="Edit Pengajuan">
                                    <i class="fas fa-pencil-alt fa-xs"></i>
                                </a>

                                <form action="{{ route('claims.destroy', $claim->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan klaim ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Batalkan Pengajuan">
                                        <i class="fas fa-times fa-xs"></i>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="mt-3">
             <style> .pagination .page-item .page-link { background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1); color: #fff; } .pagination .page-item.active .page-link { background: #4fd1c5; border-color: #4fd1c5; color: #000; } </style>
             {{ $claims->onEachSide(1)->links() }}
        </div>

        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-white-50 mb-3"></i>
            <p class="text-white-50">Belum ada klaim yang diajukan.</p>
        </div>
        @endif
    </div>
</div>
@endsection