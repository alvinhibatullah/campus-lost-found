@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('lost-items.index') }}" class="text-decoration-none text-secondary">&larr; Batal & Kembali</a>
</div>

<h3 class="mb-4">Edit Laporan Barang</h3>

<form action="{{ route('lost-items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT') <div class="card p-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="{{ $item->nama_barang }}" required>
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="form-label">Status Laporan</label>
                <select name="status" class="form-select bg-light fw-bold text-dark">
                    <option value="Searching" {{ $item->status == 'Searching' ? 'selected' : '' }}>Searching (Mencari)</option>
                    <option value="Found" {{ $item->status == 'Found' ? 'selected' : '' }}>Found (Ditemukan)</option>
                    <option value="Closed" {{ $item->status == 'Closed' ? 'selected' : '' }}>Closed (Selesai/Dibatalkan)</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Hilang</label>
                <input type="date" name="tanggal_hilang" class="form-control" value="{{ $item->tanggal_hilang }}" required>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" required>{{ $item->deskripsi }}</textarea>
            </div>
            
            <div class="col-12 mb-3">
                <label class="form-label">Koordinat Lokasi</label>
                <input type="text" name="koordinat_lokasi" class="form-control" value="{{ $item->koordinat_lokasi }}" readonly>
            </div>

            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-success px-4">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>
@endsection