@extends('layouts.app')
@section('title', 'Antrian Klaim')

@section('content')
<div class="container" style="max-width: 1100px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1">Antrian Klaim</h3>
            <div class="text-muted small">Daftar klaim yang menunggu verifikasi admin.</div>
        </div>
        <a href="{{ route('claims.index') }}" class="btn btn-light border">← Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pengaju</th>
                            <th>Barang</th>
                            <th>Status</th>
                            <th>Diajukan</th>
                            <th style="width: 360px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($claims as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->user->name ?? 'User' }}</td>
                                <td>{{ $c->item->name ?? 'Item' }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $c->status }}
                                    </span>
                                </td>
                                <td class="text-muted small">{{ $c->created_at?->format('d M Y H:i') }}</td>
                                <td>
                                    <form class="d-flex gap-2" method="POST" action="{{ route('admin.claims.verify', $c->id) }}">
                                        @csrf

                                        <select name="action" class="form-select form-select-sm" required>
                                            <option value="approved">Approve</option>
                                            <option value="rejected">Reject</option>
                                            <option value="need_more_proof">Need More Proof</option>
                                        </select>

                                        <input name="admin_note" class="form-control form-control-sm"
                                               placeholder="Catatan admin (opsional)">

                                        <button class="btn btn-primary btn-sm">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Tidak ada klaim di antrian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $claims->links() }}
    </div>
</div>
@endsection
