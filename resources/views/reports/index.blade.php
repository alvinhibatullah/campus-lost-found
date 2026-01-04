<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: white;
            padding: 40px;
        }

        h1 {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .subtitle {
            color: rgba(255,255,255,0.6);
            margin-bottom: 30px;
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(14px);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }

        /* ===== STAT CARDS ===== */
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }

        .stat-card {
            padding: 22px;
            text-align: center;
        }

        .stat-title {
            font-size: 13px;
            color: rgba(255,255,255,0.7);
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
        }

        .lost { background: linear-gradient(135deg, #ff416c33, #ff4b2b33); }
        .found { background: linear-gradient(135deg, #00c6ff33, #0072ff33); }
        .claim { background: linear-gradient(135deg, #7f00ff33, #e100ff33); }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            padding: 9px 18px;
            border-radius: 30px;
            font-size: 13px;
            text-decoration: none;
            color: white;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .btn-pdf {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
        }

        .btn-print {
            background: rgba(255,255,255,0.25);
        }

        .btn-delete {
            background: rgba(255, 0, 0, 0.25);
        }

        .btn-back {
        background: rgba(255,255,255,0.15);
        }

        .success {
            color: #4ade80;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead {
            background: rgba(255,255,255,0.1);
        }

        th, td {
            padding: 14px 16px;
            font-size: 13px;
            text-align: left;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        th:nth-child(1), td:nth-child(1) { width: 35%; }
        th:nth-child(2), td:nth-child(2) { width: 20%; }
        th:nth-child(3), td:nth-child(3) { width: 15%; }
        th:nth-child(4), td:nth-child(4) { width: 30%; text-align: center; }

        tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        tbody tr:hover {
            background: rgba(255,255,255,0.05);
        }

        .status {
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 11px;
            background: rgba(255,255,255,0.25);
            display: inline-block;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>

<h1>📊 Report Dashboard</h1>
<p class="subtitle">Ringkasan & pengelolaan laporan Campus Lost & Found</p>

@if(session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

{{-- ===== STATISTIK ===== --}}
<div class="stats">
    <div class="glass stat-card lost">
        <div class="stat-title">Lost Items</div>
        <div class="stat-value">{{ $lostCount }}</div>
    </div>

    <div class="glass stat-card found">
        <div class="stat-title">Found Items</div>
        <div class="stat-value">{{ $foundCount }}</div>
    </div>

    <div class="glass stat-card claim">
        <div class="stat-title">Claims Summary</div>
        <div class="stat-value">{{ $claimsCount }}</div>
    </div>
</div>

<div class="top-bar">
    <a href="{{ url('/menu') }}" class="btn btn-print">
        ⬅ Back to Menu
    </a>

    <a href="{{ route('reports.create') }}" class="btn btn-primary">
        ➕ Create Report
    </a>
</div>


<div class="glass" style="padding:20px;">
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Created By</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($reports as $report)
            <tr>
                <td title="{{ $report->title }}">{{ $report->title }}</td>
                <td>{{ $report->user_name ?? 'System' }}</td>
                <td><span class="status">{{ $report->status }}</span></td>
                <td>
                    <div class="actions">
                        <a href="{{ route('reports.export.pdf', $report->id) }}" class="btn btn-pdf">PDF</a>
                        <a href="{{ route('reports.print', $report->id) }}" target="_blank" class="btn btn-print">Print</a>
                        <form method="POST" action="{{ route('reports.destroy', $report->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-delete" onclick="return confirm('Hapus report ini?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align:center;">Belum ada laporan</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
