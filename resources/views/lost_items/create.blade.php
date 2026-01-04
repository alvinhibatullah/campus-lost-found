<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Report</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(14px);
            border-radius: 22px;
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            padding: 40px;
            width: 100%;
            max-width: 520px;
        }

        h1 { font-weight: 700; margin-bottom: 10px; }
        p { font-size: 14px; opacity: 0.6; margin-bottom: 25px; }

        label {
            font-size: 13px;
            margin-bottom: 6px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 14px;
            border: none;
            background: rgba(255,255,255,0.15);
            color: white;
            margin-bottom: 20px;
            outline: none;
        }

        select option { color: black; }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 30px;
            border: none;
            color: white;
            cursor: pointer;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.25);
        }
    </style>
</head>
<body>

<div class="glass">
    <h1>📝 Create Report</h1>
    <p>Isi detail laporan agar statistik dapat terbentuk otomatis.</p>

    <form method="POST" action="{{ route('reports.store') }}">
        @csrf

        <label>Nama Barang</label>
        <input type="text" name="title" placeholder="Contoh: Tas Hitam" required>

        <label>Tanggal Kejadian</label>
        <input type="date" name="incident_date" required>

        <label>Jenis Laporan</label>
        <select name="report_type_id" required>
            <option value="">-- Pilih --</option>
            <option value="1">Lost Items</option>
            <option value="2">Found Items</option>
            <option value="3">Claims Summary</option>
        </select>

        <label>Status Barang</label>
        <select name="status" required>
            <option value="GENERATED">Belum Ditemukan</option>
            <option value="ARCHIVED">Sudah Ditemukan</option>
        </select>

        <div class="actions">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">← Back</a>
            <button type="submit" class="btn btn-primary">Save Report</button>
        </div>
    </form>
</div>

</body>
</html>
