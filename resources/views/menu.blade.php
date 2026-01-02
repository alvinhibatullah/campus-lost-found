<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Utama - Campus Lost & Found</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            /* Gradient yang sama dengan Login */
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
            position: relative;
        }

        /* Dekorasi Background Bergerak */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            z-index: -1;
            animation: float 8s ease-in-out infinite;
        }
        .circle-1 { width: 400px; height: 400px; top: -100px; left: -100px; background: linear-gradient(#4facfe, #00f2fe); opacity: 0.3; }
        .circle-2 { width: 300px; height: 300px; bottom: -50px; right: -50px; background: linear-gradient(#43e97b, #38f9d7); opacity: 0.2; animation-delay: 2s; }
        .circle-3 { width: 150px; height: 150px; top: 20%; right: 10%; background: linear-gradient(#f093fb, #f5576c); opacity: 0.2; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Kartu Menu Kaca (Glassmorphism) */
        .glass-menu {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.4s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: block;
            color: white;
        }

        .glass-menu:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* Icon Styling */
        .icon-wrapper {
            width: 70px;
            height: 70px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 0 10px rgba(255,255,255,0.1);
            transition: all 0.4s;
        }

        /* Warna Khusus Tiap Icon saat Hover */
        .menu-lost:hover .icon-wrapper { background: linear-gradient(135deg, #ff416c, #ff4b2b); box-shadow: 0 0 20px rgba(255, 65, 108, 0.6); }
        .menu-found:hover .icon-wrapper { background: linear-gradient(135deg, #00b09b, #96c93d); box-shadow: 0 0 20px rgba(0, 176, 155, 0.6); }
        .menu-claim:hover .icon-wrapper { background: linear-gradient(135deg, #f8b500, #fceabb); box-shadow: 0 0 20px rgba(248, 181, 0, 0.6); }
        .menu-profile:hover .icon-wrapper { background: linear-gradient(135deg, #56ab2f, #a8e063); box-shadow: 0 0 20px rgba(86, 171, 47, 0.6); }
        .menu-stats:hover .icon-wrapper { background: linear-gradient(135deg, #667eea, #764ba2); box-shadow: 0 0 20px rgba(102, 126, 234, 0.6); }

        h5 { font-weight: 600; letter-spacing: 0.5px; }
        p { font-size: 0.85rem; opacity: 0.7; }

        /* Logout Button */
        .btn-logout {
            background: rgba(255, 0, 0, 0.2);
            border: 1px solid rgba(255, 0, 0, 0.3);
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            transition: all 0.3s;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .btn-logout:hover {
            background: rgba(255, 0, 0, 0.4);
            color: white;
        }
    </style>
</head>
<body>

    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    <div class="circle circle-3"></div>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold mb-0">Halo, {{ Auth::user()->name }}! </h2>
                <p class="text-white-50">Apa yang ingin kamu lakukan hari ini?</p>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt me-2"></i> Keluar
                </button>
            </form>
        </div>

        <div class="row g-4 justify-content-center">
            
            <div class="col-md-4 col-lg-3">
                <a href="{{ route('lost-items.index') }}" class="glass-menu menu-lost">
                    <div class="icon-wrapper">
                        <i class="fas fa-search"></i>
                    </div>
                    <h5>Barang Hilang</h5>
                    <p>Lapor kehilangan atau pantau status laporanmu.</p>
                </a>
            </div>

            <div class="col-md-4 col-lg-3">
                <a href="{{ route('found-items.index') }}" class="glass-menu menu-found">
                    <div class="icon-wrapper">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h5>Barang Temuan</h5>
                    <p>Cek daftar barang yang ditemukan di kampus.</p>
                </a>
            </div>

            <div class="col-md-4 col-lg-3">
                <a href="{{ route('claims.index') }}" class="glass-menu menu-claim">
                    <div class="icon-wrapper">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h5>Klaim Barang</h5>
                    <p>Ajukan kepemilikan barang yang ditemukan.</p>
                </a>
            </div>

            <div class="col-md-4 col-lg-3">
                <a href="{{ route('dashboard') }}" class="glass-menu menu-stats">
                    <div class="icon-wrapper">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h5>Statistik</h5>
                    <p>Lihat data ringkasan laporan di kampus.</p>
                </a>
            </div>

            <div class="col-md-4 col-lg-3">
                <a href="{{ route('home') }}" class="glass-menu menu-profile">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-astronaut"></i>
                    </div>
                    <h5>Profil Saya</h5>
                    <p>Kelola data akun dan pengaturan pribadi.</p>
                </a>
            </div>

        </div>

        <div class="text-center mt-5 text-white-50 small">
            &copy; {{ date('Y') }} KELOMPOK 4 WAD Campus Lost & Found. All rights reserved.
        </div>

    </div>

</body>
</html>