@extends('layouts.app')
@section('title', 'Lost Items')
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

    /* 3. TABEL STATIS (NO SCROLL) */
    .table-glass {
        width: 100%;
        border-collapse: separate; 
        border-spacing: 0 10px;
        color: white;
        table-layout: fixed; /* Kunci tabel biar ga melebar */
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

    /* Buttons & Map */
    .btn-action { width: 30px; height: 30px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: none; color: white; margin-left: 3px; }
    .btn-edit { background: rgba(0, 210, 255, 0.2); color: #00d2ff; }
    .btn-edit:hover { background: #00d2ff; color: white; }
    .btn-delete { background: rgba(255, 65, 108, 0.2); color: #ff416c; }
    .btn-delete:hover { background: #ff416c; color: white; }
    
    .link-map { background: rgba(255,255,255,0.1); padding: 4px 8px; border-radius: 6px; color: #00d2ff; text-decoration: none; font-size: 0.8rem; }
    .link-map:hover { background: #00d2ff; color: white; }

    /* Sticky Sidebar Biar Keren */
    .sticky-sidebar {
        position: sticky;
        top: 20px; /* Jarak dari atas pas di-scroll */
        z-index: 100;
    }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-white">Dashboard</h2>
            <p class="text-white-50 m-0">Kelola laporanmu.</p>
        </div>
        <a href="{{ route('lost-items.create') }}" class="btn btn-primary rounded-pill px-4 shadow fw-bold" style="background: #00d2ff; border: none; color: #0f2027;">
            <i class="fas fa-plus me-2"></i> Lapor Baru
        </a>
    </div>

    @if(session('success'))
    <div class="alert glass-section py-3 text-white border-0 d-flex align-items-center mb-4">
        <i class="fas fa-check-circle text-success fs-4 me-3"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4 align-items-start"> <div class="col-md-8">
            <h5 class="fw-bold mb-3 text-white"><i class="fas fa-history me-2 text-warning"></i>Riwayat Laporan</h5>
            
            <div class="glass-section px-3 py-3">
                @if($items->count() > 0)
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th width="35%">Barang</th>
                            <th width="20%">Tanggal</th>
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
                                    <div class="bg-white bg-opacity-10 rounded-3 p-2 me-2 d-flex align-items-center justify-content-center" style="width:35px;height:35px;min-width:35px;">
                                        <i class="fas fa-box text-white small"></i>
                                    </div>
                                    <div style="line-height: 1.1;">
                                        <div class="fw-bold text-white small">{{ Str::limit($item->nama_barang, 20) }}</div>
                                        <small class="text-white-50" style="font-size: 0.7rem;">{{ $item->category->nama ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="small text-white-50">
                                {{ \Carbon\Carbon::parse($item->tanggal_hilang)->format('d/m/y') }}
                            </td>
                            <td>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $item->koordinat_lokasi }}" target="_blank" class="link-map">
                                    <i class="fas fa-map-marker-alt"></i> Peta
                                </a>
                            </td>
                            <td>
                                @if($item->status == 'Searching')
                                    <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.7rem;">Cari</span>
                                @elseif($item->status == 'Found')
                                    <span class="badge bg-success rounded-pill" style="font-size: 0.7rem;">Ketemu</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill" style="font-size: 0.7rem;">Tutup</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('lost-items.edit', $item->id) }}" class="btn-action btn-edit">
                                        <i class="fas fa-pencil-alt fa-xs"></i>
                                    </a>
                                    <form action="{{ route('lost-items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete">
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
                    <p class="text-white-50">Belum ada laporan.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="col-md-4 sticky-sidebar">
            <h5 class="fw-bold mb-3 text-white"><i class="fas fa-chart-pie me-2 text-info"></i>Statistik</h5>
            
            <div class="glass-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small fw-bold text-info">USER BARU</span>
                    <span class="badge border border-white border-opacity-25 text-white-50 small">7 Hari</span>
                </div>
                
                <div style="height: 200px; width: 100%;">
                    <canvas id="userChart"></canvas>
                </div>
                
                <hr class="border-secondary border-opacity-50 my-3">
                
                <div class="text-center">
                    <small class="text-white-50 d-block mb-0 text-uppercase fw-bold" style="font-size: 0.7rem;">Laporan Aktif</small>
                    <h1 class="fw-bold text-warning mb-0">{{ $items->where('status', 'Searching')->count() }}</h1>
                    <small class="text-white-50" style="font-size: 0.7rem;">Sedang Dicari</small>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.color = 'rgba(255, 255, 255, 0.7)';
    Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';
    const ctx = document.getElementById('userChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'User',
                data: @json($chartData),
                backgroundColor: '#00d2ff', borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: false }, ticks: { display: false } },
                x: { grid: { display: false }, ticks: { font: { size: 9 } } }
            }
        }
    });
</script>
@endsection
