@extends('layouts.app')
@section('title', 'Klaim & Verifikasi')

@section('content')
<div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="display-6 fw-bold mb-1">Menu Mahasiswa</h1>
        <p class="muted mb-0">Kelola laporan kehilangan dan pantau status klaim Anda.</p>
    </div>

</div>

<hr class="my-4 divider-soft">

<div class="row g-4">
   {{-- Card Ajukan Klaim --}}
    <div class="col-lg-4">
        <div class="card card-soft shadow-soft h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    <div class="mx-auto rounded-circle border d-flex align-items-center justify-content-center"
                        style="width:76px;height:76px;background:#fff;">
                        <span class="fs-2">+</span>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Ajukan Klaim Baru</h5>
                <p class="muted mb-4">
                    Laporkan Barang temuan yang ingin dikembalikan.
                </p>
                <a href="{{ route('claims.create') }}" class="btn btn-dark w-100 btn-pill py-2">
                    MULAI LAPORAN
                </a>
            </div>
        </div>
    </div>

    {{-- Klaim Saya --}}
    <div class="col-lg-8">
        <div class="card card-soft shadow-soft h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-5">📋</span>
                        <h5 class="fw-bold mb-0">Klaim Saya</h5>
                    </div>
                    <span class="badge text-bg-dark badge-pill px-3 py-2">
                        {{ ($claims ?? collect())->whereIn('status',['pending','need_more_proof'])->count() }} Aktif
                    </span>
                </div>

                <div class="vstack gap-3">
                    @forelse($claims ?? [] as $claim)
                        @php
                            $badge = match($claim->status) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'need_more_proof' => 'info',
                                default => 'secondary'
                            };
                            $label = match($claim->status) {
                                'pending' => 'Menunggu',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'need_more_proof' => 'Butuh Bukti',
                                default => $claim->status
                            };
                        @endphp

                        <div class="border rounded-4 p-3 bg-white d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="thumb">📦</div>
                                <div>
                                    <div class="fw-semibold">
                                        {{ $claim->item?->name ?? ('Klaim #' . $claim->id) }}
                                    </div>
                                    <div class="small muted">
                                        Diajukan: {{ $claim->created_at?->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <a class="btn btn-outline-dark btn-sm btn-pill"
                                   href="{{ route('claims.show', $claim) }}">Detail</a>
                                <span class="badge text-bg-{{ $badge }} badge-pill px-3 py-2">{{ $label }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="border rounded-4 p-4 bg-white text-center muted">
                            Belum ada klaim. Klik <b>Mulai Laporan</b> untuk membuat klaim pertama.
                        </div>
                    @endforelse
                </div>

                <div class="mt-3">
                    <a href="#" class="btn btn-outline-dark w-100 btn-pill py-2">
                        Lihat Detail Semua
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Barang Temuan Terbaru (placeholder UI) --}}
    <div class="col-12">
        <div class="card card-soft shadow-soft">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-5">🔎</span>
                        <h5 class="fw-bold mb-0">Barang Temuan Terbaru</h5>
                    </div>
                    <span class="badge text-bg-light border badge-pill px-3 py-2">Terbaru Hari Ini</span>
                </div>

                <div class="row g-3">
                    @for($i=0;$i<3;$i++)
                        <div class="col-lg-4">
                            <div class="border rounded-4 p-3 bg-white d-flex align-items-center gap-3">
                                <div class="thumb">🧾</div>
                                <div class="w-100">
                                    <div class="fw-semibold text-truncate">Item Contoh {{ $i+1 }}</div>
                                    <div class="small muted">Hari ini, 09:30</div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="mt-3">
                    <a href="#" class="btn btn-outline-dark w-100 btn-pill py-2">
                        Lihat Semua Barang Temuan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
