<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klaim Saya - Campus Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Biar mirip screenshot: badge pill + warna halus */
        .badge-soft {
            border: 1px solid transparent;
            padding: .45rem .75rem;
            border-radius: 999px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }
        .dot {
            width: .5rem;
            height: .5rem;
            border-radius: 999px;
            display: inline-block;
        }

        .soft-submitted { background:#F1F5F9; color:#475569; border-color:#E2E8F0; }
        .soft-review    { background:#FEF9C3; color:#92400E; border-color:#FDE68A; }
        .soft-needinfo  { background:#E0E7FF; color:#3730A3; border-color:#C7D2FE; }
        .soft-approved  { background:#DCFCE7; color:#166534; border-color:#BBF7D0; }
        .soft-rejected  { background:#FEE2E2; color:#991B1B; border-color:#FECACA; }
        .soft-cancelled { background:#F1F5F9; color:#64748B; border-color:#E2E8F0; }

        .icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #F1F5F9;
            display: grid;
            place-items: center;
            color: #64748B;
            flex: 0 0 auto;
        }

        .table thead th {
            font-size: .75rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #64748B;
        }

        .card-rounded { border-radius: 16px; }
    </style>
</head>

<body class="bg-light">
<div class="container py-4">

    {{-- Header sama seperti contoh kamu --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Klaim Saya</h3>
            <small class="text-muted">Campus Lost & Found</small>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-dark btn-sm">Logout</button>
        </form>
    </div>

    <div class="mb-4">
        <p class="text-muted mb-0">
            Pantau status laporan barang hilang dan temuan Anda di sini. Cek pembaharuan secara berkala.
        </p>
    </div>

    {{-- Filter Card --}}
    <div class="card shadow-sm card-rounded mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('claims.index') }}">
                <div class="row g-3 align-items-end">

                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                {{-- Search icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input type="text"
                                   class="form-control"
                                   name="q"
                                   value="{{ request('q') }}"
                                   placeholder="Cari ID Klaim atau nama barang...">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Filter Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @php
                                $opts = [
                                  'submitted' => 'Submitted',
                                  'under_review' => 'Under Review',
                                  'need_more_proof' => 'Need Info',
                                  'approved' => 'Accepted',
                                  'rejected' => 'Rejected',
                                  'cancelled' => 'Cancelled',
                                ];
                            @endphp
                            @foreach($opts as $val => $label)
                                <option value="{{ $val }}" {{ request('status')===$val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 d-grid">
                        {{-- tombol kecil seperti ikon download di screenshot (sebenarnya ini submit filter) --}}
                        <button type="submit" class="btn btn-outline-secondary" title="Terapkan filter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                            </svg>
                        </button>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">Terapkan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm card-rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm card-rounded">{{ session('error') }}</div>
    @endif

    {{-- Table Card --}}
    <div class="card shadow-sm card-rounded">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="border-bottom bg-white">
                        <tr>
                            <th class="px-4 py-3">ID Klaim</th>
                            <th class="px-4 py-3">Nama Barang</th>
                            <th class="px-4 py-3">Tanggal Klaim</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-end"></th>
                        </tr>
                    </thead>
                    <tbody>

                    @forelse($claims as $claim)
                        @php
                            $itemName = $claim->item->name ?? ('Barang #' . $claim->item_id);
                            $itemCategory = $claim->item->category ?? null;

                            $code = '#CLM-' . now()->format('Y') . '-' . str_pad($claim->id, 3, '0', STR_PAD_LEFT);

                            // mapping badge style
                            $badgeClass = match($claim->status) {
                                'under_review' => 'soft-review',
                                'submitted' => 'soft-submitted',
                                'need_more_proof' => 'soft-needinfo',
                                'approved' => 'soft-approved',
                                'rejected' => 'soft-rejected',
                                'cancelled' => 'soft-cancelled',
                                default => 'soft-submitted',
                            };

                            $dotColor = match($claim->status) {
                                'under_review' => '#F59E0B',
                                'submitted' => '#94A3B8',
                                'need_more_proof' => '#6366F1',
                                'approved' => '#22C55E',
                                'rejected' => '#EF4444',
                                'cancelled' => '#CBD5E1',
                                default => '#94A3B8',
                            };
                        @endphp

                        <tr class="border-bottom">
                            <td class="px-4 py-3">
                                <a href="{{ route('claims.show', $claim) }}" class="fw-semibold text-primary text-decoration-none">
                                    {{ $code }}
                                </a>
                            </td>

                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box">
                                        {{-- icon item --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $itemName }}</div>
                                        <div class="text-muted small">{{ $itemCategory ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3 text-muted">
                                {{ optional($claim->created_at)->format('d M Y') }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="badge-soft {{ $badgeClass }}">
                                    <span class="dot" style="background: {{ $dotColor }}"></span>
                                    {{ $claim->statusLabel() }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="d-flex justify-content-end gap-3">

                                    {{-- Batalkan muncul kalau submitted (mirip screenshot) --}}
                                    @if($claim->status === 'submitted')
                                        <form method="POST" action="{{ route('claims.cancel', $claim) }}"
                                              onsubmit="return confirm('Batalkan klaim ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-link text-danger fw-semibold p-0 text-decoration-none">
                                                {{-- trash icon --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" class="me-1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4"/>
                                                </svg>
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Download PDF kalau approved (kalau route ada) --}}
                                    @if($claim->status === 'approved' && Route::has('claims.pdf'))
                                        <a href="{{ route('claims.pdf', $claim) }}"
                                           class="btn btn-link fw-semibold p-0 text-decoration-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" class="me-1">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                                            </svg>
                                            Download PDF
                                        </a>
                                    @endif

                                    <a href="{{ route('claims.show', $claim) }}"
                                       class="btn btn-link text-muted fw-semibold p-0 text-decoration-none">
                                        Lihat Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-5 text-center text-muted">
                                Belum ada klaim.
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Footer: range + pagination --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 px-4 py-3 border-top bg-white">
                <div class="text-muted small">
                    Menampilkan <span class="fw-semibold">{{ $claims->firstItem() ?? 0 }}</span>
                    sampai <span class="fw-semibold">{{ $claims->lastItem() ?? 0 }}</span>
                    dari <span class="fw-semibold">{{ $claims->total() }}</span> hasil
                </div>

                <div>
                    {{-- Pagination default Laravel (Tailwind). Kalau kamu mau Bootstrap pagination, lihat catatan di bawah --}}
                    {{ $claims->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>