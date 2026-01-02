<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS GLOBAL (Sama dengan Dashboard) */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: white;
            display: flex; align-items: center; justify-content: center;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            width: 100%; max-width: 700px;
        }

        .avatar-big {
            width: 100px; height: 100px;
            background: rgba(0, 210, 255, 0.1);
            border: 2px solid #00d2ff;
            color: #00d2ff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 3rem; font-weight: bold;
            margin-right: 25px;
            overflow: hidden; /* Penting agar gambar tidak keluar dari lingkaran */
        }

        .btn-warning-custom {
            background: #ffc107; border: none; color: #212529; font-weight: 600;
        }
        .btn-warning-custom:hover { background: #e0a800; }

        .btn-outline-light:hover { background: rgba(255,255,255,0.1); color: white; }

        /* Hiasan Background */
        .circle-bg {
            position: fixed; border-radius: 50%; z-index: -1; filter: blur(80px);
        }
        .c1 { width: 400px; height: 400px; top: -100px; right: -100px; background: #4facfe; opacity: 0.2; }
        .c2 { width: 300px; height: 300px; bottom: -50px; left: -50px; background: #43e97b; opacity: 0.2; }
    </style>
</head>
<body>

    <div class="circle-bg c1"></div>
    <div class="circle-bg c2"></div>

    <div class="container">
        
        <div class="d-flex justify-content-between align-items-center mb-4 mx-auto" style="max-width: 700px;">
            <h3 class="fw-bold mb-0">Profil Saya</h3>
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success bg-opacity-75 border-0 text-white mx-auto mb-4" style="max-width: 700px; background: rgba(25, 135, 84, 0.5);">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="glass-card mx-auto">
            <div class="d-flex align-items-center mb-4">
                <div class="avatar-big">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="avatar" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        {{ substr($user->name, 0, 1) }}
                    @endif
                </div>
                
                <div>
                    <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                    <p class="text-white-50 mb-0 fs-5">{{ $user->email }}</p>
                    <span class="badge bg-info text-dark mt-2">Active Member</span>
                </div>
            </div>

            <hr class="border-secondary border-opacity-50">

            <div class="mt-4">
                <a href="{{ route('profile.edit') }}" class="btn btn-warning-custom px-4 py-2 rounded-pill">
                    <i class="bi bi-pencil-square me-2"></i> Edit Profil
                </a>
            </div>
        </div>

    </div>

</body>
</html>