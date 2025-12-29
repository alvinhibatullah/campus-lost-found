@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-history me-2"></i>Riwayat Laporan Saya</h2>
    <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Buat Laporan Baru
    </a>
</div>

<div class="card p-4">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Tanggal Hilang</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td class="fw-bold">{{ $item->nama_barang }}</td>
                <td>{{ $item->category->nama ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_hilang)->format('d M Y') }}</td>
                <td>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $item->koordinat_lokasi }}" target="_blank" class="text-decoration-none">
                        <i class="fas fa-map-marker-alt text-danger me-1"></i> Lihat Peta
                    </a>
                </td>
                <td>
                    @if($item->status == 'Searching')
                        <span class="badge bg-warning text-dark">Searching</span>
                    @elseif($item->status == 'Found')
                        <span class="badge bg-success">Found</span>
                    @else
                        <span class="badge bg-secondary">Closed</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('lost-items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary me-1">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <form action="{{ route('lost-items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus laporan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">Belum ada laporan kehilangan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection