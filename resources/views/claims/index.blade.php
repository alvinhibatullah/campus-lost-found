<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menu Klaim Barang - Campus Lost & Found</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* 1. GLOBAL STYLE */
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        min-height: 100vh;
        color: white;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    a { text-decoration: none; color: inherit; transition: 0.3s; }

    /* 2. NAVBAR STYLE */
    .navbar-top {
        background: linear-gradient(to right, #38bdf8, #0284c7);
        padding: 15px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        position: sticky;
        top: 0;
        z-index: 100;
        width: 100%;
    }

    .nav-brand {
        font-weight: 700;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .searchbar {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 999px;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 320px;
        backdrop-filter: blur(5px);
    }
    .searchbar input {
        border: none; outline: none; background: transparent;
        width: 100%; color: white; font-size: 14px;
    }
    .searchbar input::placeholder { color: rgba(255,255,255,0.7); }

    /* Nav Links (Bantuan, dll) */
    .nav-link-custom {
        color: rgba(255,255,255,0.9);
        font-weight: 600;
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 8px;
    }
    .nav-link-custom:hover {
        background: rgba(255,255,255,0.1);
        color: white;
    }

    /* User Section */
    .user-section {
        display: flex; align-items: center; gap: 15px;
        background: rgba(255, 255, 255, 0.15);
        padding: 5px 5px 5px 20px;
        border-radius: 50px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .profile-link {
        display: flex; align-items: center; gap: 12px;
        text-decoration: none; color: white;
        padding-right: 10px;
        border-radius: 30px;
        transition: background 0.2s;
    }
    .profile-link:hover { background: rgba(255,255,255,0.1); }

    .user-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        object-fit: cover; border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .user-name { font-size: 13px; font-weight: 600; text-align: right; line-height: 1.2; }
    .user-role { font-size: 10px; opacity: 0.8; display: block; }
    
    .btn-logout {
        background: white; color: #0284c7; border: none;
        padding: 8px 20px; border-radius: 30px; font-size: 12px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 5px;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-logout:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.2); }

    /* 3. PAGE CONTENT */
    .page { max-width: 980px; margin: 40px auto; padding: 0 20px; }

    .backlink {
        display: inline-flex; align-items: center; gap: 10px;
        color: rgba(255,255,255,0.7); font-weight: 600;
        margin-bottom: 20px;
        padding: 8px 16px;
        background: rgba(255,255,255,0.1);
        border-radius: 30px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .backlink:hover { background: rgba(255,255,255,0.2); color: white; }

    h1 { font-weight: 700; font-size: 36px; margin-bottom: 5px; }
    .subtitle { color: rgba(255,255,255,0.6); margin-bottom: 35px; font-size: 16px; }

    /* 4. CARDS */
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media(max-width: 768px){ .grid { grid-template-columns: 1fr; } }

    .cardx {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        padding: 25px;
        min-height: 200px;
        display: flex; flex-direction: column; justify-content: space-between;
        transition: transform 0.3s ease;
    }
    .cardx:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255,255,255,0.3);
    }

    .cardx h3 { font-size: 20px; font-weight: 700; margin-bottom: 8px; }
    .cardx p { font-size: 14px; color: rgba(255,255,255,0.6); line-height: 1.5; margin: 0; }

    .iconbtn {
        width: 50px; height: 50px; border-radius: 15px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: grid; place-items: center; color: white; font-size: 20px;
        box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4);
    }
    .iconbtn.green {
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        box-shadow: 0 4px 15px rgba(0, 114, 255, 0.4);
    }

    .rowtop { display: flex; justify-content: space-between; align-items: flex-start; gap: 15px; margin-bottom: 20px; }
    .linkrow { display: flex; align-items: center; gap: 10px; color: #38bdf8; font-weight: 700; font-size: 14px; margin-top: auto; }
    .linkrow:hover { color: white; text-decoration: underline; }
    
    .pill {
        display: inline-block; padding: 6px 12px; border-radius: 20px;
        font-weight: 600; font-size: 12px;
        background: rgba(56, 189, 248, 0.2); color: #7dd3fc;
        border: 1px solid rgba(56, 189, 248, 0.3);
        width: fit-content; margin-bottom: 15px;
    }

    @media (max-width: 992px) {
        .navbar-top { flex-direction: column; gap: 15px; padding: 20px; height: auto; }
        .user-section { width: 100%; justify-content: space-between; }
        .searchbar { width: 100%; }
        .page { margin-top: 20px; }
        .nav-actions { width: 100%; justify-content: center; }
    }
  </style>
</head>

<body>

  {{-- TOP NAVBAR --}}
  <nav class="navbar-top">
    <div class="nav-brand">
        <i class="fas fa-search-location"></i>
        <span>Campus Lost & Found</span>
    </div>

    {{-- Search Bar --}}
    <div class="searchbar d-none d-md-flex">
        <i class="fas fa-search text-white-50"></i>
        <input type="text" placeholder="Cari ID Klaim atau Nama Barang..." />
    </div>

    <div class="d-flex align-items-center gap-3 nav-actions">
        {{-- 1. TOMBOL BANTUAN (Sesuai Request: Link Pagar) --}}
        <a href="#" class="nav-link-custom">Bantuan</a>

        {{-- 2. USER SECTION --}}
        <div class="user-section">
            {{-- Link ke Profile HOME (profile.show), bukan Edit --}}
            <a href="{{ route('home') }}" class="profile-link" title="Lihat Profil Saya">
                <div class="d-none d-sm-block text-end">
                    <span class="user-role">Welcome,</span>
                    <div class="user-name">{{ Auth::user()->name ?? 'Guest' }}</div>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'G') }}&background=random&color=fff&bold=true" class="user-avatar" alt="Profile">
            </a>

            {{-- Tombol Logout --}}
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">
                    Logout
                </button>
            </form>
        </div>
    </div>
  </nav>

  {{-- PAGE CONTENT --}}
  <div class="page">

    <a class="backlink" href="{{ route('main.menu') }}">
      <i class="fas fa-arrow-left"></i> Kembali ke Menu
    </a>

    <h1>Menu Klaim Barang</h1>
    <div class="subtitle">Ajukan klaim barang hilang dan pantau seluruh aktivitas Anda.</div>

    @php
      $activeCount = $activeCount ?? 0;
    @endphp

    <div class="grid">

      {{-- Card 1 --}}
      <div class="cardx">
        <div class="rowtop">
          <div>
            <h3>Telusuri Barang Hilang</h3>
            <p>Lihat daftar barang hilang yang tersedia dan ajukan klaim kepemilikan.</p>
          </div>
          <div class="iconbtn" aria-hidden="true">
            <i class="fas fa-search"></i>
          </div>
        </div>

        <a class="linkrow" href="{{ route('found-items.index') }}">
          <i class="fas fa-external-link-alt"></i> Lihat Barang Tersedia
        </a>
      </div>

      {{-- Card 2 --}}
      <div class="cardx">
        <div class="rowtop">
          <div>
            <h3>Kelola Klaim Saya</h3>
            <p>Pantau status dan riwayat klaim yang pernah Anda ajukan.</p>
          </div>
          <div class="iconbtn green" aria-hidden="true">
            <i class="fas fa-folder-open"></i>
          </div>
        </div>

        <div class="pill">
            <i class="fas fa-info-circle me-1"></i> {{ $activeCount }} Klaim Aktif
        </div>

        <a class="linkrow" href="{{ route('claims.index') }}">
            <i class="fas fa-arrow-right"></i> Kelola Klaim Saya
        </a>
      </div>

    </div>
  </div>

</body>
</html>