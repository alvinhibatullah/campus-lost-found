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

        .circle-bg {
            position: fixed; border-radius: 50%; z-index: -1; filter: blur(80px);
        }
        .c1 { width: 400px; height: 400px; top: -100px; left: -100px; background: #4facfe; opacity: 0.2; }
    </style>
</head>
<body>

    <div class="circle-bg c1"></div>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4 mx-auto" style="max-width: 600px;">
            <h3 class="fw-bold mb-0">Edit Profil</h3>
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill">Kembali ke Menu</a>
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
                    <input type="text" name="name" class="form-control form-control-glass" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white-50">Alamat Email</label>
                    <input type="email" name="email" class="form-control form-control-glass text-white-50" value="{{ $user->email }}" readonly style="background: rgba(0, 0, 0, 0.4); cursor: not-allowed;">
                    <small class="text-white-50" style="font-size: 0.7rem;">*Email tidak dapat diubah.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white-50">NIM</label>
                        <input type="text" name="nim" class="form-control form-control-glass" placeholder="Contoh: 120220001" value="{{ old('nim', $user->nim) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-white-50">Angkatan</label>
                        <input type="number" name="angkatan" class="form-control form-control-glass" placeholder="Contoh: 2023" value="{{ old('angkatan', $user->angkatan) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-white-50">Fakultas</label>
                        <select name="fakultas" class="form-control form-control-glass">
                            <option value="" class="text-dark">-- Pilih Fakultas --</option>
                            @php
                                $fakultasList = ['Fakultas Teknik Elektro', 'Fakultas Rekayasa Industri', 'Fakultas Informatika', 'Fakultas Ekonomi Bisnis', 'Fakultas Komunikasi Bisnis', 'Fakultas Industri Kreatif', 'Fakultas Ilmu Terapan'];
                            @endphp
                            @foreach($fakultasList as $f)
                                <option value="{{ $f }}" class="text-dark" {{ old('fakultas', $user->fakultas) == $f ? 'selected' : '' }}>{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-white-50">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control form-control-glass" placeholder="Contoh: S1 Informatika" value="{{ old('jurusan', $user->jurusan) }}">
                    </div>
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