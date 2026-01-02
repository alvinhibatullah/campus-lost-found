<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Control Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* 1. GLOBAL STYLE (TEMA GELAP) */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }

        /* 2. GLASS CARD STYLE (UNTUK KARTU) */
        .glass-card {
            background: rgba(255, 255, 255, 0.05); /* Transparan */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 25px;
            margin-bottom: 20px;
        }

        /* 3. TYPOGRAPHY */
        .text-mute-custom {
            color: rgba(255, 255, 255, 0.6);
        }
        h5 { font-weight: 600; margin-bottom: 20px; }

        /* 4. AVATAR */
        .avatar-circle {
            width: 70px; height: 70px;
            background: rgba(0, 210, 255, 0.1);
            border: 2px solid #00d2ff;
            color: #00d2ff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
        }

        /* 5. BUTTONS */
        .btn-primary-custom {
            background: #00d2ff; border: none; color: #0f2027; font-weight: 600;
        }
        .btn-primary-custom:hover { background: #33ddff; color: #0f2027; }

        .btn-outline-custom {
            background: transparent; border: 1px solid #33ddff; color: #33ddff;
        }
        .btn-outline-custom:hover { background: #33ddff; color: #0f2027; }

        .btn-danger-custom {
            background: rgba(220, 53, 69, 0.2); border: 1px solid #dc3545; color: #ff6b6b;
        }
        .btn-danger-custom:hover { background: #dc3545; color: white; }

        .btn-logout {
            border: 1px solid rgba(255, 255, 255, 0.2); color: white; border-radius: 5px; padding: 5px 15px;
            background: transparent; transition: 0.3s;
        }
        .btn-logout:hover { background: rgba(255,255,255,0.1); border-color: white; }

        /* 6. BACKGROUND BLOBS (HIASAN) */
        .circle-bg {
            position: fixed; border-radius: 50%; z-index: -1;
            filter: blur(80px);
        }
        .c1 { width: 400px; height: 400px; top: -100px; left: -100px; background: #4facfe; opacity: 0.2; }
        .c2 { width: 300px; height: 300px; bottom: -50px; right: -50px; background: #43e97b; opacity: 0.2; }
        
        /* Alert Custom */
        .alert-glass {
            background: rgba(13, 202, 240, 0.15);
            border: 1px solid rgba(13, 202, 240, 0.3);
            color: #0dcaf0;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="circle-bg c1"></div>
    <div class="circle-bg c2"></div>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-0 fw-bold">User Control Dashboard</h2>
                <p class="text-mute-custom small">Campus Lost & Found</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout btn-sm">Logout</button>
            </form>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="glass-card">
                    <h5>Profil Singkat</h5>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-circle me-3">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ Auth::user()->name }}</div>
                            <div class="small text-mute-custom">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.show') }}" class="btn btn-primary-custom">Lihat Profil</a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-custom">Edit Profil</a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                
                <div class="glass-card">
                    <h5>Aktivitas & Informasi</h5>
                    <p class="text-mute-custom small">
                        Untuk tahap 40%, dashboard menampilkan kontrol profil dasar. Modul log aktivitas, roles, permissions, dan grafik dapat ditambahkan setelah ini.
                    </p>

                    <div class="alert alert-glass p-3 small mb-0">
                        <i class="bi bi-info-circle me-2"></i> 
                        Status: Login Google berhasil dan akun tersimpan di database.
                    </div>
                </div>

                <div class="glass-card" style="border-color: rgba(220, 53, 69, 0.3);">
                    <h5 class="text-danger">Deaktivasi Akun</h5>
                    <p class="text-mute-custom small">
                        Tombol ini akan menonaktifkan akun (menghapus akun dari database jika hard delete).
                    </p>
                    
                    <form method="POST" action="{{ route('profile.deactivate') }}" onsubmit="return confirm('Yakin ingin menonaktifkan akun?');">
                        @csrf
                        <button type="submit" class="btn btn-danger-custom btn-sm px-4">
                            Deaktivasi Akun
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
</html>