<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            /* Background Biru Gradient yang Hidup */
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); 
            /* Alternatif Biru Lebih Terang: */
            /* background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Hiasan Lingkaran di Belakang (Agar tidak flat) */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            z-index: 0;
            animation: float 6s ease-in-out infinite;
        }
        .circle-1 { width: 300px; height: 300px; top: -50px; left: -50px; background: linear-gradient(#4facfe, #00f2fe); opacity: 0.4; }
        .circle-2 { width: 200px; height: 200px; bottom: 50px; right: -30px; background: linear-gradient(#43e97b, #38f9d7); opacity: 0.3; }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        /* Kartu Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px); /* Efek Buram */
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            color: white;
            z-index: 10;
            text-align: center;
        }

        .app-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: -webkit-linear-gradient(#00c6ff, #0072ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 10px rgba(0,198,255,0.5));
        }

        h2 {
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* Tombol Kustom */
        .btn-google {
            background: white;
            color: #333;
            font-weight: 600;
            border: none;
            padding: 12px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .btn-google:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            background: #f8f9fa;
        }

        .btn-dev {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 50px;
            padding: 8px 15px;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .btn-dev:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        .feature-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 2rem;
        }
        .feature-item {
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.8);
        }
        .feature-item i {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: #00c6ff;
        }
    </style>
</head>
<body>

    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>

    <div class="container d-flex justify-content-center">
        
        <div class="glass-card">
            <div class="app-icon">
                <i class="fas fa-search-location"></i>
            </div>

            <h2>LOST & FOUND</h2>
            <p>Sistem Pelaporan Barang Hilang & Temuan<br>Lingkungan Kampus</p>

            <div class="feature-icons">
                <div class="feature-item">
                    <i class="fas fa-box-open"></i>
                    <span>Lapor</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>Pantau</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-hand-holding-heart"></i>
                    <span>Klaim</span>
                </div>
            </div>

            <a href="{{ url('login/google') }}" class="btn btn-google w-100 mb-4">
                <img src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png" alt="G" width="20">
                Masuk dengan Google
            </a>

            <div class="d-flex align-items-center justify-content-center mb-3">
                <div style="height: 1px; background: rgba(255,255,255,0.2); width: 40%;"></div>
                <span class="mx-2 text-white-50" style="font-size: 0.8rem;">Dev Only</span>
                <div style="height: 1px; background: rgba(255,255,255,0.2); width: 40%;"></div>
            </div>

            <a href="{{ url('/bypass-login') }}" class="btn btn-dev w-100">
                <i class="fas fa-user-secret me-2"></i> Mode Pengembang (Bypass)
            </a>
            
            <div class="mt-4 text-white-50" style="font-size: 0.75rem;">
                &copy; {{ date('Y') }} Campus Lost & Found Team
            </div>
        </div>

    </div>

</body>
</html>