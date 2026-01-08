<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* 1. GLOBAL STYLE */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: white;
            padding: 40px; 
            margin: 0;
            overflow-x: hidden;
        }

        /* 2. NAVBAR STYLE */
        .navbar-top {
            background: linear-gradient(to right, #38bdf8, #0284c7);
            padding: 12px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            margin: -40px -40px 40px -40px; 
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            font-weight: 700;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.15);
            padding: 5px 5px 5px 20px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            height: 48px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            line-height: 1.1;
            margin-right: 5px;
        }

        .user-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .btn-logout {
            background: white;
            color: #0284c7;
            border: none;
            padding: 0 20px;
            height: 38px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-logout:hover {
            background: #f0f9ff;
            transform: translateY(-1px);
        }

        /* 3. DASHBOARD COMPONENTS */
        h1 { font-weight: 700; margin-bottom: 6px; }
        .subtitle { color: rgba(255,255,255,0.6); margin-bottom: 30px; }
        
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(14px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            padding: 20px;
            margin-bottom: 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }
        .stat-card { padding: 22px; text-align: center; }
        .stat-title { font-size: 13px; color: rgba(255,255,255,0.7); margin-bottom: 6px; }
        .stat-value { font-size: 32px; font-weight: 700; }
        
        .lost { background: linear-gradient(135deg, #ff416c33, #ff4b2b33); border: 1px solid rgba(255, 65, 108, 0.3); }
        .found { background: linear-gradient(135deg, #00c6ff33, #0072ff33); border: 1px solid rgba(0, 198, 255, 0.3); }
        .claim { background: linear-gradient(135deg, #7f00ff33, #e100ff33); border: 1px solid rgba(127, 0, 255, 0.3); }

        .btn { padding: 9px 18px; border-radius: 30px; font-size: 13px; text-decoration: none; color: white; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s; }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); }
        .btn-pdf { background: linear-gradient(135deg, #ff416c, #ff4b2b); }
        .btn-print { background: rgba(255,255,255,0.25); }
        .btn-delete { background: rgba(255, 0, 0, 0.25); color: #ff8a8a; }
        
        table { width: 100%; border-collapse: collapse; }
        thead { background: rgba(255,255,255,0.1); }
        th, td { padding: 12px 14px; font-size: 13px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .status { padding: 4px 12px; border-radius: 30px; font-size: 10px; background: rgba(255,255,255,0.25); display: inline-block; font-weight: 600; }
        .text-small { font-size: 11px; color: rgba(255,255,255,0.5); }
        
        @media (max-width: 992px) {
            .dashboard-grid { grid-template-columns: 1fr; }
            .navbar-top { flex-direction: column; gap: 15px; padding: 20px; height: auto; }
            .user-section { width: 100%; justify-content: space-between; height: auto; padding: 10px; }
        }
    </style>
</head>
<body>

<nav class="navbar-top">
    <div class="nav-brand">
        <i class="fas fa-search-location"></i>
        <span>Campus Lost & Found</span>
    </div>

    <div class="user-section">
        <div class="user-info">
            <span class="user-label">Halo,</span>
            <span class="user-name" title="{{ Auth::user()->name ?? 'Guest' }}">
                {{ Str::limit(Auth::user()->name ?? 'Guest User', 20) }}
            </span>
        </div>
        
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'G') }}&background=random&color=fff&bold=true" class="user-avatar" alt="Profile">

        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout">
                Logout <i class="fas fa-sign-out-alt" style="margin-left:5px;"></i>
            </button>
        </form>
    </div>
</nav>

<h1>📊 Report & Global Dashboard</h1>
<p class="subtitle">Integrasi data real-time : Lost Items and Found items</p>

@if(session('success'))
    <div style="background: rgba(74, 222, 128, 0.2); color: #4ade80; padding: 15px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- ===== STATISTIK UTAMA ===== --}}
<div class="stats">
    <div class="glass stat-card lost">
        <div class="stat-title">Total Barang Hilang</div>
        <div class="stat-value">{{ $lostCount }}</div>
    </div>
    <div class="glass stat-card found">
        <div class="stat-title">Barang Ditemukan</div>
        <div class="stat-value">{{ $foundCount }}</div>
    </div>
    <div class="glass stat-card claim">
        <div class="stat-title">Kasus Selesai (Closed)</div>
        <div class="stat-value">{{ $claimsCount }}</div>
    </div>
</div>

{{-- ===== DASHBOARD GRID ===== --}}
<div class="dashboard-grid">
    
    <div class="glass">
        <h3 style="margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-satellite-dish text-info"></i> Live Feed: Aktivitas Terbaru
        </h3>
        <table>
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Kategori</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentItems as $item)
                <tr>
                    <td>
                        <strong>{{ $item->nama_barang }}</strong><br>
                        {{-- UPDATE: Menambahkan .addHours(7) untuk memperbaiki timezone --}}
                        <span class="text-small">
                            <i class="far fa-clock"></i> 
                            {{ \Carbon\Carbon::parse($item->created_at)->addHours(7)->locale('id')->diffForHumans() }}
                        </span>
                    </td>
                    <td>{{ $item->kategori }}</td>
                    <td>
                        {{-- Logika Badge Status Lengkap (Termasuk Claims) --}}
                        @if($item->status == 'Searching')
                            <span class="status" style="background:#ff416c; color:white;">Hilang</span>
                        
                        @elseif($item->status == 'Unclaimed')
                            <span class="status" style="background:#4fd1c5; color:black;">Ditemukan</span>
                        
                        @elseif($item->status == 'Found')
                            <span class="status" style="background:#198754; color:white;">Ketemu</span>
                        
                        @elseif($item->status == 'Claimed')
                            <span class="status" style="background:#0284c7; color:white;">Diklaim</span>

                        {{-- STATUS BARU DARI MODUL CLAIMS --}}
                        @elseif($item->status == 'pending')
                            <span class="status" style="background:#f59e0b; color:black;">Verifikasi Klaim</span>
                        @elseif($item->status == 'approved')
                            <span class="status" style="background:#3b82f6; color:white;">Klaim Disetujui</span>
                        @elseif($item->status == 'taken')
                            <span class="status" style="background:#10b981; color:white;">Selesai Diambil</span>
                        @elseif($item->status == 'rejected')
                            <span class="status" style="background:#ef4444; color:white;">Klaim Ditolak</span>
                        
                        @else
                            <span class="status">Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center" style="padding:20px; color:rgba(255,255,255,0.5);">Belum ada aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="glass" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <h3 style="margin-top:0; font-size: 14px; text-align: center; margin-bottom: 20px;">Statistik Gabungan</h3>
        <div style="width: 100%; height: 250px;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

{{-- ===== TABEL REPORT ===== --}}
<div class="glass">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin:0;"><i class="fas fa-file-alt" style="margin-right: 8px;"></i> Internal Reports Management</h3>
        <div style="display: flex; gap: 10px;">
            <a href="{{ url('/menu') }}" class="btn btn-print"><i class="fas fa-arrow-left"></i> Menu</a>
            <a href="{{ route('reports.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Report Baru</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="35%">Title</th>
                <th width="20%">Created By</th>
                <th width="15%">Status</th>
                <th width="30%" style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($reports as $report)
            <tr>
                <td>
                    <span style="font-weight:600;">{{ $report->title }}</span><br>
                    {{-- UPDATE: Menambahkan .addHours(7) untuk memperbaiki timezone --}}
                    <span class="text-small">{{ \Carbon\Carbon::parse($report->created_at)->addHours(7)->locale('id')->isoFormat('D MMMM Y') }}</span>
                </td>
                <td>
                    @if($report->user_name)
                        <i class="fas fa-user-circle"></i> {{ $report->user_name }}
                    @else
                        <span style="opacity:0.5;">- System -</span>
                    @endif
                </td>
                <td>
                    @if($report->status == 'Searching')
                        <span class="status" style="background: #eab308; color: black;">Searching</span>
                    @elseif($report->status == 'Found')
                        <span class="status" style="background: #22c55e; color: white;">Found</span>
                    @else
                        <span class="status" style="background: #64748b; color: white;">{{ $report->status }}</span>
                    @endif
                </td>
                <td style="text-align: center;">
                    <a href="{{ route('reports.export.pdf', $report->id) }}" class="btn btn-pdf" style="padding: 5px 12px; font-size: 11px;">PDF</a>
                    <a href="{{ route('reports.print', $report->id) }}" target="_blank" class="btn btn-print" style="padding: 5px 12px; font-size: 11px;">Print</a>
                    <form method="POST" action="{{ route('reports.destroy', $report->id) }}" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-delete" style="padding: 5px 12px; font-size: 11px;" onclick="return confirm('Hapus report ini?')">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center; padding: 20px; color:rgba(255,255,255,0.5);">Belum ada laporan internal.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- SCRIPT CHART --}}
<script>
    const ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                data: {!! json_encode($chartValues) !!},
                backgroundColor: [
                    'rgba(255, 65, 108, 0.8)',  // Merah
                    'rgba(79, 209, 197, 0.8)',  // Hijau Teal
                    'rgba(168, 85, 247, 0.8)',  // Ungu
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: 'white', boxWidth: 10, padding: 20 } }
            }
        }
    });
</script>

</body>
</html>