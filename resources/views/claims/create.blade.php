@extends('layouts.app')
@section('title', 'Buat Laporan Baru')

@section('content')
<div class="container" style="max-width: 980px;">

    {{-- Back --}}
    <div class="mb-3">
        <a href="{{ route('claims.index') }}" class="text-decoration-none small">
            ← Kembali ke Dashboard
        </a>
    </div>

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="fw-bold mb-2" style="letter-spacing:-0.5px;">Buat Laporan Baru</h1>
        <p class="muted mb-0">
             Isi formulir ini untuk melaporkan barang agar dapat dikembalikan ke pemiliknya.
        </p>
    </div>

    <div class="card card-soft shadow-soft">
        <div class="card-body p-4 p-lg-5">

            {{-- Badge / Label (opsional, biar mirip UI) --}}
            <div class="d-flex justify-content-center mb-4">
                <div class="px-4 py-2 bg-light rounded-4 border small">
                    📦 <span class="fw-semibold ms-1">Laporkan Barang Ditemukan</span>
                </div>
            </div>

            <form method="POST" action="{{ route('claims.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Hidden type (optional) --}}
                <input type="hidden" name="report_type" value="found">

                {{-- Section: Informasi Barang --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge text-bg-primary-subtle text-primary border rounded-circle p-2">🧾</span>
                    <h5 class="fw-bold mb-0">Informasi Barang</h5>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="item_name" class="form-control"
                               placeholder="Contoh: Laptop Dell XPS 13" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori Barang <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            <option>Elektronik</option>
                            <option>Aksesori</option>
                            <option>Dokumen</option>
                            <option>Pakaian</option>
                            <option>Lainnya</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Ciri Khusus Barang</label>
                        <input type="text" name="details[unique_marks]" class="form-control"
                               placeholder="Contoh: Stiker GitHub di bagian belakang, goresan di sudut kiri...">
                        <div class="form-text">Sebutkan detail unik yang membedakan barang ini.</div>
                    </div>
                </div>

                <hr class="my-4 divider-soft">

                {{-- Section: Waktu & Lokasi --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge text-bg-primary-subtle text-primary border rounded-circle p-2">📍</span>
                    <h5 class="fw-bold mb-0">Waktu & Lokasi</h5>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi Ditemukan <span class="text-danger">*</span></label>
                        <input type="text" name="incident_location" class="form-control"
                               placeholder="Contoh: Gedung A, Ruang 304" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Ditemukan <span class="text-danger">*</span></label>
                        <input type="date" name="incident_date" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Kondisi Barang</label>
                        <select name="details[item_condition]" class="form-select">
                            <option value="">Pilih Kondisi</option>
                            <option>Baik</option>
                            <option>Tergores</option>
                            <option>Rusak ringan</option>
                            <option>Rusak berat</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4 divider-soft">

                {{-- Section: Deskripsi & Bukti --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge text-bg-primary-subtle text-primary border rounded-circle p-2">📝</span>
                    <h5 class="fw-bold mb-0">Deskripsi & Bukti</h5>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi Lengkap <span class="text-danger">*</span></label>
                    <textarea name="claim_reason" class="form-control" rows="4"
                              placeholder="Ceritakan kronologi kejadian dan detail barang secara lengkap..."
                              required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Informasi Tambahan</label>
                    <textarea name="details[additional_info]" class="form-control" rows="3"
                              placeholder="Informasi kontak alternatif atau instruksi khusus..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Foto Barang Ditemukan</label>

                    <div class="dropzone mb-2">
                        <div class="fs-5 mb-1">📷</div>
                        <div class="fw-semibold">
                            Klik untuk upload <span class="muted fw-normal">atau seret dan lepas</span>
                        </div>
                        <div class="small muted mb-3">PNG, JPG, PDF up to 10MB</div>
                        <input class="form-control" type="file" name="proof_files[]" multiple>
                        <div class="small muted mt-2">
                            Foto kondisi barang saat ditemukan untuk verifikasi kepemilikan.
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mt-4">
                    <a href="{{ route('claims.index') }}" class="btn btn-light border btn-pill px-4">
                        Batalkan
                    </a>
                    <button type="submit" class="btn btn-primary btn-pill px-4 shadow-soft">
                        ▶ Submit Laporan Ditemukan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Help card --}}
    <div class="card card-soft shadow-soft mt-4">
        <div class="card-body p-4 d-flex gap-3">
            <div class="fs-4">ℹ️</div>
            <div>
                <div class="fw-semibold">Butuh Bantuan?</div>
                <div class="small muted">
                    Jika Anda tidak yakin kategori barang atau mengalami masalah saat upload bukti,
                    silakan hubungi <b>Admin</b> atau cek <b>FAQ</b>.
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
