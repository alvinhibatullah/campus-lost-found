<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Selamat datang, {{ Auth::user()->name }}!</h1>
    <p>Email: {{ Auth::user()->email }}</p>
    <p>Ini adalah halaman dashboard sederhana.</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>