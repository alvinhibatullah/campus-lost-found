@extends('layouts.app')
@section('title', 'Menu Klaim')

@push('styles')
<style>
    body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important; min-height: 100vh; color: white; overflow-x: hidden; }
    .circle-bg { position: fixed; border-radius: 50%; background: rgba(255, 255, 255, 0.1); filter: blur(80px); z-index: -1; animation: float 8s ease-in-out infinite; }
    .c1 { width: 500px; height: 500px; top: -100px; left: -100px; background: #4facfe; opacity: 0.4; }
    .c2 { width: 400px; height: 400px; bottom: -50px; right: -50px; background: #43e97b; opacity: 0.3; animation-delay: 2s; }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-30px); } }

    .glass-menu-card {
        background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px; padding: 40px 30px; text-align: center; height: 100%; transition: 0.3s;
    }
    .glass-menu-card:hover { transform: translateY(-10px); background: rgba(255, 255, 255, 0.1); border-color: #4fd1c5; }
    
    .icon-circle { width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.1); margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #4fd1c5; transition: 0.3s; }
    .glass-menu-card:hover .icon-circle { background: #4fd1c5; color: #0f2027; transform: scale(1.1); }
</style>
@endpush

@section('content')
<div class="circle-bg c1"></div>
<div class="circle-bg c2"></div>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-white">Menu Klaim Barang</h1>
        <p class="text-white-50">Pusat pengelolaan klaim barang hilang di kampus.</p>
    </div>

    <div class="row justify-content-center g-4">
        <div class="col-md-5 col-lg-4">
            <div class="glass-menu-card">
                <div class="icon-circle"><i class="fas fa-search"></i></div>
                <h3 class="fw-bold text-white mb-3">Telusuri Barang</h3>
                <p class="text-white-50 mb-4">Lihat daftar barang temuan dan ajukan klaim.</p>
                <a href="{{ route('claims.browse') }}" class="btn btn-light w-100 rounded-pill fw-bold" style="color: #0f2027;">Cari Barang</a>
            </div>
        </div>

        <div class="col-md-5 col-lg-4">
            <div class="glass-menu-card">
                <div class="icon-circle"><i class="fas fa-folder-open"></i></div>
                <h3 class="fw-bold text-white mb-3">Klaim Saya</h3>
                <p class="text-white-50 mb-4">Pantau status verifikasi dan riwayat klaim Anda.</p>
                <a href="{{ route('claims.my-claims') }}" class="btn w-100 rounded-pill fw-bold" style="background: #4fd1c5; color: #0f2027; border:none;">Kelola Klaim</a>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('main.menu') }}" class="btn btn-outline-light rounded-pill px-4">
            <i class="fas fa-grid me-2"></i> Kembali ke Dashboard Utama
        </a>
    </div>
</div>
@endsection