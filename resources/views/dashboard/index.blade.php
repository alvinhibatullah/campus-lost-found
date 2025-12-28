<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report & Dashboard - Campus L&F</title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f1f5f9;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            width: 230px;
            height: 100vh;
            background: linear-gradient(180deg, #2563eb, #1e40af);
            color: #ffffff;
            padding: 25px;
        }

        .sidebar h2 {
            color: #ffffff;
            margin-bottom: 40px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .sidebar a {
            display: block;
            color: #e0e7ff;
            text-decoration: none;
            margin: 15px 0;
            font-weight: 500;
        }

        .sidebar a:hover {
            color: #ffffff;
            transform: translateX(6px);
            transition: 0.2s;
        }

        /* ===== MAIN ===== */
        .main {
            margin-left: 260px;
            padding: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        /* ===== SUMMARY ===== */
        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .card h3 {
            color: #475569;
            font-size: 14px;
        }

        .card p {
            font-size: 30px;
            font-weight: bold;
            margin-top: 10px;
            color: #1e293b;
        }

        /* ===== CHARTS ===== */
        .charts {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-box {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        /* ===== REPORT TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        table th, table td {
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        table th {
            background: #f8fafc;
            font-size: 14px;
            color: #334155;
        }

        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .completed {
            background: #dcfce7;
            color: #166534;
        }

        .pending {
            background: #fef9c3;
            color: #854d0e;
        }

    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Campus L&F</h2>
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('reports.index') }}">Reports</a>
</div>

<!-- MAIN -->
<div class="main">
    <h1>Report & Dashboard</h1>

    <!-- SUMMARY -->
    <div class="cards">
        <div class="card">
            <h3>Total Lost Items</h3>
            <p>{{ $totalLost }}</p>
        </div>
        <div class="card">
            <h3>Total Found Items</h3>
            <p>{{ $totalFound }}</p>
        </div>
        <div class="card">
            <h3>Resolved Claims</h3>
            <p>{{ $resolvedClaims }}%</p>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="charts">
        <div class="chart-box">
            <h3>Lost vs Found Items</h3>
            <canvas id="lostFoundChart"></canvas>
        </div>
        <div class="chart-box">
            <h3>Frequent Loss Locations</h3>
            <canvas id="locationChart"></canvas>
        </div>
    </div>

    <!-- REPORT TABLE -->
    <h3>Report Management</h3>
    <table>
        <thead>
            <tr>
                <th>Report Type</th>
                <th>Period</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->type }}</td>
                <td>{{ $report->period }}</td>
                <td>
                    <span class="status {{ $report->status == 'Completed' ? 'completed' : 'pending' }}">
                        {{ $report->status }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- CHART SCRIPT -->
<script>
    new Chart(document.getElementById('lostFoundChart'), {
        type: 'bar',
        data: {
            labels: ['Lost', 'Found'],
            datasets: [{
                label: 'Items',
                data: [{{ $totalLost }}, {{ $totalFound }}],
                backgroundColor: ['#ef4444', '#22c55e']
            }]
        }
    });

    new Chart(document.getElementById('locationChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($locations->pluck('location')) !!},
            datasets: [{
                data: {!! json_encode($locations->pluck('total')) !!},
                backgroundColor: ['#3b82f6','#6366f1','#0ea5e9','#22c55e']
            }]
        }
    });
</script>

</body>
</html>
