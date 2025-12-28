<!DOCTYPE html>
<html>
<head>
    <title>Create Report</title>
</head>
<body>

<h2>Create Report</h2>

<form method="POST" action="{{ route('reports.store') }}">
    @csrf

    <label>Judul Report</label><br>
    <input type="text" name="title" required><br><br>

    <label>Jenis Report</label><br>
    <select name="report_type_id">
        @foreach($types as $type)
            <option value="{{ $type->id }}">{{ $type->name }}</option>
        @endforeach
    </select><br><br>

    <button type="submit">Generate Report</button>
</form>

<br>
<a href="{{ route('reports.index') }}">← Back</a>

</body>
</html>
