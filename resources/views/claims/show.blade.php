@extends('layouts.app')
@section('title', 'Detail Klaim')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h3 class="fw-bold mb-0">Detail Klaim #{{ $claim->id }}</h3>
        <div class="muted">Item: {{ $claim->item?->name ?? '-' }}</div>
    </div>
    <a class="btn btn-outline-dark btn-pill" href="{{ route('claims.index') }}">← Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-soft shadow-soft">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Informasi Klaim</h5>
                <div class="mb-2"><b>Status:</b> {{ $claim->status }}</div>
                <div class="mb-2"><b>Alasan/Deskripsi:</b><br>{{ $claim->claim_reason }}</div>
                <div class="mb-2"><b>Waktu Kejadian:</b> {{ $claim->incident_at ?? '-' }}</div>
                <div class="mb-2"><b>Lokasi Kejadian:</b> {{ $claim->incident_location ?? '-' }}</div>
                <div class="mb-2"><b>Catatan Admin:</b> {{ $claim->admin_note ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-soft shadow-soft mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Detail Tambahan</h5>
                @forelse($claim->details as $d)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="muted">{{ $d->key }}</span>
                        <span class="fw-semibold">{{ $d->value }}</span>
                    </div>
                @empty
                    <div class="muted">Tidak ada detail tambahan.</div>
                @endforelse
            </div>
        </div>

        <div class="card card-soft shadow-soft">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Lampiran Bukti</h5>
                @forelse($claim->attachments as $a)
                    <div class="d-flex align-items-center justify-content-between border rounded-3 p-2 mb-2 bg-white">
                        <div class="small">
                            <div class="fw-semibold">{{ $a->original_name }}</div>
                            <div class="muted">{{ $a->file_type }}</div>
                        </div>
                        <a class="btn btn-sm btn-outline-primary"
                           href="{{ asset('storage/'.$a->file_path) }}" target="_blank">Lihat</a>
                    </div>
                @empty
                    <div class="muted">Belum ada file bukti.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
