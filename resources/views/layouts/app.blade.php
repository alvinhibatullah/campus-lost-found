<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Campus Lost & Found')</title>

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f6f7fb; }
        .card-soft { border: 1px solid #e9ecef; border-radius: 16px; }
        .shadow-soft { box-shadow: 0 8px 24px rgba(16,24,40,.06); }
        .btn-pill, .badge-pill { border-radius: 999px; }
        .muted { color: #6c757d; }
        .avatar {
            width: 38px; height: 38px; border-radius: 999px;
            display: inline-flex; align-items: center; justify-content: center;
            background: #e9ecef; color: #495057; font-weight: 700;
        }
        .thumb {
            width: 52px; height: 52px; border-radius: 14px;
            border: 1px dashed #cfd4da; background: #fff;
            display:flex; align-items:center; justify-content:center;
            color:#adb5bd; font-weight:700;
        }
        .dropzone {
            border: 2px dashed #cfd4da;
            border-radius: 14px;
            background: #fff;
            padding: 22px;
            text-align: center;
        }
        .nav-link.active { font-weight: 600; }
        .divider-soft { border-top: 1px dashed #d9dee4; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container py-1">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <span class="me-2"></span> Campus Lost & Found
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="topnav">
            <ul class="navbar-nav mx-auto gap-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('claims*') ? 'active' : '' }}" href="{{ route('claims.index') }}">
                        Klaim & Verifikasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/claims*') ? 'active' : '' }}" href="{{ route('admin.claims.queue') }}">
                        Admin
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-dark btn-sm btn-pill">🔔 Notifikasi</button>
                <div class="text-end d-none d-lg-block">
                    <div class="fw-semibold">Budiyono</div>
                    <div class="small muted">MAHASISWA</div>
                </div>
                <div class="avatar">BS</div>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Validasi gagal:</div>
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

    <footer class="mt-5 pt-4 text-center muted small">
        <div class="divider-soft mb-3"></div>
        © {{ date('Y') }} Campus Lost & Found • All rights reserved
    </footer>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
