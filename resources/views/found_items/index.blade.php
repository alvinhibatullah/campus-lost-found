@extends('layouts.app')
@section('title', 'Found Items')

@push('styles')
<style>
    /* 1. BACKGROUND & GLOBAL */
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important;
        min-height: 100vh;
        color: white;
        overflow-x: hidden;
    }
    
    /* Animasi Blob Background */
    .circle-bg {
        position: fixed; border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        filter: blur(80px); z-index: -1;
        animation: float 8s ease-in-out infinite;
    }
    .c1 { width: 500px; height: 500px; top: -100px; left: -100px; background: #4facfe; opacity: 0.4; }
    .c2 { width: 400px; height: 400px; bottom: -50px; right: -50px; background: #43e97b; opacity: 0.3; animation-delay: 2s; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-30px); } }

    /* 2. GLASS PANEL */
    .glass-section {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        padding: 20px;
    }

    /* 3. TABEL STATIS */
    .table-glass {
        width: 100%;
        border-collapse: separate; 
        border-spacing: 0 10px;
        color: white;
        table-layout: fixed;
    }
    .table-glass th {
        text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;
        color: rgba(255,255,255,0.5); padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .table-glass tbody tr {
        background: rgba(0, 0, 0, 0.2); transition: 0.2s;
    }
    .table-glass tbody tr:hover {
        background: rgba(0, 0, 0, 0.4); transform: translateY(-2px);
    }
    .table-glass td {
        padding: 15px 10px; border: none; vertical-align: middle; word-wrap: break-word;
    }
    .table-glass td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
    .table-glass td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }

    /* Buttons & Custom Teal Colors */
    .btn-action { width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: none; color: white; margin-left: 3px; transition: 0.3s; }
    .btn-result { background: rgba(255, 255, 255, 0.1); color: white; }
    .btn-result:hover { background: rgba(255, 255, 255, 0.2); }
    .btn-edit { background: rgba(79, 209, 197, 0.2); color: #4fd1c5; }
    .btn-edit:hover { background: #4fd1c5; color: #0f2027; }
    .btn-delete { background: rgba(255, 65, 108, 0.2); color: #ff416c; }
    .btn-delete:hover { background: #ff416c; color: white; }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-white">Dashboard Temuan</h2>
            <p class="text-white-50 m-0">Kelola laporan barang yang kamu temukan.</p>
        </div>
        
        <a href="{{ route('main.menu') }}" class="btn btn-outline-light rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Menu
        </a>
    </div>

    @if(session('success'))
    <div class="alert glass-section py-3 text-white border-0 d-flex align-items-center mb-4">
        <i class="fas fa-check-circle text-success fs-4 me-3"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4 align-items-start"> 
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-white"><i class="fas fa-history me-2 text-warning"></i>Riwayat Laporan Temuan</h5>
                
                <a href="{{ route('found-items.create') }}" class="btn btn-primary rounded-pill px-4 shadow fw-bold" style="background: #4fd1c5; border: none; color: #0f2027;">
                    <i class="fas fa-plus me-2"></i> Lapor Baru
                </a>
            </div>
            
            <div class="glass-section px-3 py-3">
                @if($items->count() > 0)
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th width="35%">Barang</th>
                            <th width="20%">Tanggal Ditemukan</th>
                            <th width="20%">Lokasi</th>
                            <th width="15%">Status</th>
                            <th width="10%" class="text-end">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-10 rounded-3 p-2 me-2 d-flex align-items-center justify-content-center" style="width:45px;height:45px;min-width:45px; overflow:hidden;">
                                        @if($item->foto_barang)
                                            <img src="{{ asset('storage/' . $item->foto_barang) }}" style="width:100%; height:100%; object-fit:cover;" class="rounded">
                                        @else
                                            <i class="fas fa-box text-white-50 small"></i>
                                        @endif
                                    </div>
                                    <div style="line-height: 1.1;">
                                        <div class="fw-bold text-white small">{{ Str::limit($item->nama_barang, 25) }}</div>
                                        <small class="text-white-50" style="font-size: 0.7rem;">ID: #{{ $item->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="small text-white-50">
                                {{ \Carbon\Carbon::parse($item->tanggal_ditemukan)->format('d/m/y') }}
                            </td>
                            <td class="small">
                                <span class="text-white-50"><i class="fas fa-map-marker-alt me-1" style="color: #4fd1c5;"></i>{{ $item->lokasi_ditemukan }}</span>
                            </td>
                            <td>
                                @if($item->status == 'Unclaimed')
                                    <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.7rem;">Unclaimed</span>
                                @elseif($item->status == 'Claimed')
                                    <span class="badge bg-success rounded-pill" style="font-size: 0.7rem;">Claimed</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill" style="font-size: 0.7rem;">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    
                                    <a href="{{ route('found-items.result', $item->id) }}" target="_blank" class="btn-action btn-result" title="Lihat Result">
                                        <i class="fas fa-file-alt fa-xs"></i>
                                    </a>

                                    <a href="{{ route('found-items.edit', $item->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-pencil-alt fa-xs"></i>
                                    </a>

                                    <form action="{{ route('found-items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus laporan temuan ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus">
                                            <i class="fas fa-trash fa-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-white-50 mb-3"></i>
                    <p class="text-white-50">Belum ada laporan temuan barang.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection