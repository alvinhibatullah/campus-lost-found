<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - User Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">User Control Dashboard</h3>
            <small class="text-muted">Campus Lost & Found</small>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-dark btn-sm">Logout</button>
        </form>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Profil Singkat</h5>

                    <div class="d-flex align-items-center gap-3">
                        <div style="width:64px;height:64px;border-radius:50%;overflow:hidden;background:#eee;">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="avatar" style="width:100%;height:100%;object-fit:cover;">
                            @endif
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <div class="text-muted small">{{ $user->email }}</div>
                        </div>
                    </div>

                    <hr>

                    <a href="{{ route('profile.show') }}" class="btn btn-primary w-100 mb-2">Lihat Profil</a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-warning w-100">Edit Profil</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Aktivitas & Informasi</h5>
                    <p class="text-muted mb-0">
                        Untuk tahap 40%, dashboard menampilkan kontrol profil dasar.  
                        Modul log aktivitas, roles, permissions, dan grafik dapat ditambahkan setelah ini.
                    </p>

                    <hr>

                    <div class="alert alert-info mb-0">
                        Status: Login Google berhasil dan akun tersimpan di database.
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h5 class="card-title text-danger">Deaktivasi Akun</h5>
                    <p class="text-muted">
                        Tombol ini akan menonaktifkan akun (menghapus akun dari database jika hard delete).
                    </p>

                    <form method="POST" action="{{ route('profile.deactivate') }}"
                          onsubmit="return confirm('Yakin ingin menonaktifkan akun?');">
                        @csrf
                        <button type="submit" class="btn btn-danger">Deaktivasi Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>