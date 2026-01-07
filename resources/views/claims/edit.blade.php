@extends('layouts.app')
@section('title', 'Edit Pengajuan Klaim')

@push('styles')
<style>
    body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #0f2027, #203a43, #2c5364) !important; min-height: 100vh; color: white; }
    .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    .form-glass { background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1); color: white; border-radius: 10px; padding: 12px; }
    .form-glass:focus { background: rgba(0, 0, 0, 0.5); border-color: #fbbf24; color: white; box-shadow: none; }
    .item-summary { background: rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; border-left: 4px solid #fbbf24; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('claims.my-claims') }}" class="btn btn-outline-light rounded-pill me-3"><i class="fas fa-arrow-left"></i></a>
                <h2 class="fw-bold mb-0 text-white">Edit Pengajuan Klaim</h2>
            </div>

            <div class="glass-card">
                
                {{-- Info Barang (Read Only) --}}
                <div class="item-summary mb-4">
                    <h5 class="fw-bold text-white mb-2">{{ $claim->item_name }}</h5>
                    <div class="d-flex gap-4 text-white-50 small mb-2">
                        <span><i class="fas fa-tag me-1 text-warning"></i> {{ $claim->category }}</span>
                        <span><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $claim->location_found ?? '-' }}</span>
                    </div>
                    <p class="text-white-50 m-0 small">{{ $claim->description }}</p>
                </div>

                {{-- Form Edit --}}
                <form action="{{ route('claims.update', $claim->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- PENTING UNTUK UPDATE --}}

                    <div class="mb-4">
                        <label class="fw-bold mb-2">Alasan Klaim & Kontak</label>
                        <textarea name="claim_reason" rows="6" class="form-control form-glass" required>{{ old('claim_reason', $claim->claim_reason) }}</textarea>
                        <small class="text-white-50">*Silakan perbarui alasan atau nomor kontak di dalam kolom teks di atas.</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn fw-bold py-3 rounded-3" 
                                style="background: #fbbf24; color: #0f2027; border: none;">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection