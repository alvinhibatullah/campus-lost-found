<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 10px;
            border: 1px solid #000;
        }
        .label {
            width: 30%;
            font-weight: bold;
        }
    </style>
</head>
<body onload="window.print()">

    <h2>Campus Lost & Found Report</h2>

    <table>
        <tr>
            <td class="label">Judul Report</td>
            <td>{{ $report->title }}</td>
        </tr>
        <tr>
            <td class="label">Dibuat Oleh</td>
            <td>{{ $report->user_name }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>{{ $report->status }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}</td>
        </tr>
    </table>

</body>
</html>
