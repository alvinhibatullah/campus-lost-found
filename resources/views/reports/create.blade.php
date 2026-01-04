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
            max-width: 560px;
        }

        h1 {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 14px;
            color: rgba(255,255,255,0.6);
            margin-bottom: 30px;
        }

        label {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
            opacity: 0.85;
        }

        input, select {
            width: 100%;
            padding: 14px 16px;
            border-radius: 14px;
            border: none;
            background: rgba(255,255,255,0.15);
            color: white;
            margin-bottom: 22px;
            font-size: 14px;
            outline: none;
        }

        input::placeholder {
            color: rgba(255,255,255,0.6);
        }

        select option {
            color: black;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 32px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            color: white;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.45);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.25);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.35);
        }

        .error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            padding: 12px 16px;
            border-radius: 14px;
            margin-bottom: 20px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="glass">
    <h1>📝 Create Report</h1>
    <p class="subtitle">
        Masukkan data laporan kehilangan atau temuan barang.
    </p>

    @if ($errors->any())
        <div class="error">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reports.store') }}">
        @csrf

        <!-- TITLE -->
        <label>Nama Barang / Judul Report</label>
        <input
            type="text"
            name="title"
            placeholder="Contoh: Tas Hitam Nike"
            required
        >

        <!-- DATE -->
        <label>Tanggal Kejadian</label>
        <input
            type="date"
            name="incident_date"
            required
        >

        <!-- TYPE -->
        <label>Jenis Laporan</label>
        <select name="report_type_id" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="1">Lost Items</option>
            <option value="2">Found Items</option>
            <option value="3">Claims</option>
        </select>

        <!-- STATUS -->
        <label>Status Barang</label>
        <select name="status" required>
            <option value="">-- Pilih Status --</option>
            <option value="Searching">Searching</option>
            <option value="Found">Found</option>
            <option value="Closed">Closed</option>
        </select>

        <!-- ACTIONS -->
        <div class="actions">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                ← Back
            </a>

            <button
                type="submit"
                class="btn btn-primary"
                onclick="this.disabled=true;this.innerText='Menyimpan...';this.form.submit();"
            >
                💾 Simpan Report
            </button>
        </div>
    </form>
</div>

</body>
</html>
