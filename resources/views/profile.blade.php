<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Profil Saya</h3>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:72px;height:72px;border-radius:50%;overflow:hidden;background:#eee;">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="avatar" style="width:100%;height:100%;object-fit:cover;">
                    @endif
                </div>
                <div>
                    <div class="fw-semibold fs-5">{{ $user->name }}</div>
                    <div class="text-muted">{{ $user->email }}</div>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="btn btn-warning">Edit Profil</a>
        </div>
    </div>

</div>
</body>
</html>