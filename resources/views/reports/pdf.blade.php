<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Kehilangan</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .header p {
            margin: 0;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 25px 0;
            font-size: 14px;
        }

        .content {
            margin-top: 20px;
        }

        .content p {
            margin: 6px 0;
        }

        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 6px 4px;
        }

        .label {
            width: 35%;
        }

        .signature {
            margin-top: 60px;
            width: 100%;
        }

        .signature td {
            width: 50%;
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <h2>CAMPUS LOST & FOUND</h2>
        <p>Pusat Layanan Barang Hilang & Temuan Mahasiswa</p>
        <p>Telkom University Bandung</p>
    </div>

    <!-- TITLE -->
    <div class="title">
        BERITA ACARA KEHILANGAN
    </div>

    <!-- OPENING -->
    <div class="content">
        <p>
            Pada hari ini,
            <strong>{{ \Carbon\Carbon::parse($report->created_at)->translatedFormat('l, d F Y') }}</strong>,
            telah dilaporkan kehilangan barang dengan rincian sebagai berikut:
        </p>

        <!-- DETAIL TABLE -->
        <table>
            <tr>
                <td class="label">Nama Pelapor</td>
                <td>: {{ $report->user_name }}</td>
            </tr>
            <tr>
                <td class="label">Nama Barang</td>
                <td>: {{ $report->title }}</td>
            </tr>
            <tr>
                <td class="label">Kategori</td>
                <td>: Aksesori</td>
            </tr>
            <tr>
                <td class="label">Tanggal Hilang</td>
                <td>: {{ \Carbon\Carbon::parse($report->created_at)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Lokasi / Koordinat</td>
                <td>: -6.973304, 107.630696</td>
            </tr>
            <tr>
                <td class="label">Status Saat Ini</td>
                <td>: Searching</td>
            </tr>
            <tr>
                <td class="label">Deskripsi / Ciri-ciri</td>
                <td>: hitam</td>
            </tr>
            <tr>
                <td class="label">Foto Barang</td>
                <td>: -</td>
            </tr>
        </table>
    </div>

    <!-- SIGNATURE -->
    <table class="signature">
        <tr>
            <td></td>
            <td>
                Pelapor,<br><br><br><br>
                <strong>{{ $report->user_name }}</strong>
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        {{ now()->format('n/j/y, h:i A') }} · Laporan Kehilangan #{{ $report->id }} · 127.0.0.1
    </div>

</body>
</html>
