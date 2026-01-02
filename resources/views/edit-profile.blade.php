<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
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
            width: 100%; max-width: 600px;
        }

        /* Input Form Gelap Transparan */
        .form-control-glass {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px 15px;
            border-radius: 8px;
        }
        .form-control-glass:focus {
            background: rgba(0, 0, 0, 0.3);
            border-color: #00d2ff;
            color: white;
            box-shadow: 0 0 10px rgba(0, 210, 255, 0.2);
            outline: none;
        }
        
        .btn-success-custom {
            background: #198754; border: none; padding: 10px 20px; font-weight: 600;
        }
        .btn-success-custom:hover { background: #157347; box-shadow: 0 0 15px rgba(25, 135, 84, 0.4); }

        /* Hiasan Background */
        .circle-bg {
            position: fixed; border-radius: 50%; z-index: -1; filter: blur(80px);
        }
        .c1 { width: 400px; height: 400px; top: -100px; left: -100px; background: #4facfe; opacity: 0.2; }
    </style>
</head>
<body>

    <div class="circle-bg c1"></div>

    <div class="container">
        
        <div class="d-flex justify-content-between align-items-center mb-4 mx-auto" style="max-width: 600px;">
            <h3 class="fw-bold mb-0">Edit Profil</h3>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill">Batal</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 mx-auto mb-4 bg-opacity-75 text-white" style="max-width: 600px;">
                <div class="fw-semibold mb-2">Periksa input Anda:</div>
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="glass-card mx-auto">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-white-50">Nama Lengkap</label>
                    <input type="text" 
                           name="name" 
                           class="form-control form-control-glass" 
                           value="{{ old('name', $user->name) }}" 
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label text-white-50">Alamat Email</label>
                    <input type="email" 
                           name="email" 
                           class="form-control form-control-glass" 
                           value="{{ old('email', $user->email) }}" 
                           required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success-custom rounded-pill">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>