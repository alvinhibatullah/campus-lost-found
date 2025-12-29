<!DOCTYPE html>
<html>
<head>
    <title>Report Management</title>
</head>
<body>

<h2>Report Management</h2>

@if(session('success'))
<p style="color:green">{{ session('success') }}</p>
@endif

<a href="{{ route('reports.create') }}">+ Create Report</a>

<table border="1" cellpadding="8" cellspacing="0">
<tr>
    <th>Title</th>
    <th>Created By</th>
    <th>Status</th>
    <th>Action</th>
</tr>

@foreach($reports as $report)
<tr>
    <td>{{ $report->title }}</td>
    <td>{{ $report->user_name }}</td>
    <td>{{ $report->status }}</td>
    <td>
        <form method="POST" action="{{ route('reports.destroy', $report->id) }}">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Hapus report?')">Delete</button>
        </form>
    </td>
</tr>
@endforeach
</table>

<br>
<a href="{{ route('dashboard') }}">← Back to Dashboard</a>

</body>
</html>
