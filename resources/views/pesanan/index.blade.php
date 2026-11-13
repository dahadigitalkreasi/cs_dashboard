
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daftar Pesanan - CS Meta Ads</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            color: #1e293b;
            font-family: system-ui, sans-serif;
        }

        .navbar-custom {
            background: #111827;
        }

        .page-title {
            font-size: clamp(21px, 4vw, 27px);
            font-weight: 800;
        }

        .filter-card,
        .order-card {
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 15px;
        }

        .filter-card {
            padding: 20px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            min-height: 43px;
            font-size: 14px;
        }

        .order-card {
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: .2s;
        }

        .order-card:hover {
            border-color: #bfdbfe;
            box-shadow: 0 6px 20px rgba(15,23,42,.06);
        }

        .buyer-name {
            font-size: 16px;
            font-weight: 750;
            overflow-wrap: anywhere;
        }

        .order-id {
            color: #64748b;
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .order-details {
            border-top: 1px solid #f1f5f9;
            padding-top: 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .info-label {
            color: #64748b;
            flex-shrink: 0;
        }

        .info-value {
            text-align: right;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .status-badge {
            display: inline-block;
            border-radius: 8px;
            padding: 6px 9px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-info {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-default {
            background: #e2e8f0;
            color: #475569;
        }

        .detail-button {
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            padding: 11px;
            margin-top: auto;
        }

        .pagination {
            flex-wrap: wrap;
            gap: 4px;
        }

        .page-link {
            border-radius: 8px !important;
        }

        @media (max-width: 575.98px) {
            .main-content {
                padding-left: 14px;
                padding-right: 14px;
            }

            .filter-card {
                padding: 15px;
            }

            .order-card {
                padding: 16px;
                gap: 10px;
            }

            .navbar-brand {
                font-size: 15px;
            }

            .buyer-name {
                font-size: 15px;
            }

            .status-badge {
                font-size: 10px;
            }

            .filter-actions .btn {
                flex: 1;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark navbar-custom py-3">
    <div class="container">

        <a href="{{ route('dashboard') }}"
           class="navbar-brand fw-bold">
            <i class="bi bi-grid-1x2-fill me-1"></i>
            CS META ADS
        </a>

        <a href="{{ route('dashboard') }}"
           class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Dashboard
        </a>

    </div>
</nav>

<main class="container main-content py-4">

    <!-- JUDUL -->

    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-2 mb-4">

        <div>
            <h1 class="page-title mb-1">
                Daftar Pesanan
            </h1>

            <p class="text-secondary small mb-0">
                Pantau pesanan Meta Ads.
            </p>
        </div>

        <span class="badge bg-primary rounded-pill px-3 py-2">
            {{ number_format($pesanan->total(), 0, ',', '.') }}
            Pesanan
        </span>

    </div>

    <!-- FILTER -->

    <div class="filter-card shadow-sm mb-4">

        <form method="GET"
              action="{{ route('pesanan.index') }}">

            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label">
                        Cari Pesanan
                    </label>

                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="search"
                            name="cari"
                            class="form-control"
                            placeholder="Nama, ID, HP, atau resi..."
                            value="{{ request('cari') }}"
                        >
                    </div>
                </div>

                <div class="col-6">
                    <label class="form-label">
                        Bulan
                    </label>

                    <select name="bulan" class="form-select">
                        @foreach($namaBulan as $nomor => $nama)
                            <option value="{{ $nomor }}"
                                @selected($bulan === $nomor)>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6">
                    <label class="form-label">
                        Tahun
                    </label>

                    <select name="tahun" class="form-select">
                        @foreach($tahunTersedia as $pilihanTahun)
                            <option value="{{ $pilihanTahun }}"
                                @selected($tahun === $pilihanTahun)>
                                {{ $pilihanTahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 d-flex gap-2 filter-actions">

                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>
                        Terapkan
                    </button>

                    <a href="{{ route('pesanan.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </a>

                </div>

            </div>
        </form>

    </div>

    <!-- DAFTAR PESANAN -->

    <div class="row g-3">

        @forelse($pesanan as $item)

            @php
                $status = strtoupper(
                    trim($item->status ?? '')
                );

                $statusClass = match ($status) {
                    'SELESAI' => 'status-success',
                    'DI KIRIM', 'DIKIRIM', 'KIRIM'
                        => 'status-info',
                    'BATAL', 'DIBATALKAN',
                    'PENGEMBALIAN', 'PENGIRIMAN GAGAL'
                        => 'status-danger',
                    'PROSES', 'DIPROSES'
                        => 'status-warning',
                    default => 'status-default',
                };
            @endphp

            <div class="col-12 col-md-6 col-xl-4">

                <article class="order-card shadow-sm">

                    <!-- PEMBELI DAN STATUS -->

                    <div class="d-flex justify-content-between
                                align-items-start gap-2">

                        <div class="flex-grow-1"
                             style="min-width:0;">

                            <div class="buyer-name">
                                {{ $item->nama_pembeli ?? 'Tanpa Nama' }}
                            </div>

                            <div class="order-id mt-1">
                                <i class="bi bi-receipt me-1"></i>
                                {{ $item->id_pesanan }}
                            </div>

                        </div>

                        <span class="status-badge {{ $statusClass }}">
                            {{ $item->status ?? 'BELUM ADA STATUS' }}
                        </span>

                    </div>

                    <!-- INFORMASI RINGKAS -->

                    <div class="order-details">

                        <div class="info-row">
                            <span class="info-label">
                                <i class="bi bi-calendar3 me-1"></i>
                                Tanggal
                            </span>

                            <span class="info-value">
                                {{ $item->tgl_order?->format('d/m/Y') ?? '-' }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <i class="bi bi-person me-1"></i>
                                CS
                            </span>

                            <span class="info-value">
                                {{ $item->cs ?? '-' }}
                            </span>
                        </div>

                        <div class="info-row mb-0">
                            <span class="info-label">
                                <i class="bi bi-wallet2 me-1"></i>
                                Harga
                            </span>

                            <span class="info-value">
                                {{ $item->harga === null
                                    ? '-'
                                    : 'Rp ' . number_format(
                                        (float) $item->harga,
                                        0, ',', '.'
                                    )
                                }}
                            </span>
                        </div>

                    </div>

                    <!-- DETAIL -->

                    <a href="{{ route('pesanan.show', $item->id) }}"
                       class="btn btn-outline-primary detail-button w-100">

                        <i class="bi bi-eye me-1"></i>
                        Lihat Detail

                    </a>

                </article>

            </div>

        @empty

            <div class="col-12">
                <div class="filter-card text-center py-5">
                    <i class="bi bi-inbox fs-1 text-secondary"></i>

                    <h6 class="fw-bold mt-3">
                        Tidak Ada Pesanan
                    </h6>

                    <p class="text-secondary small mb-0">
                        Tidak ditemukan pesanan pada bulan ini
                        atau sesuai pencarian.
                    </p>
                </div>
            </div>

        @endforelse

    </div>

    <!-- PAGINATION -->

    @if($pesanan->hasPages())
        <div class="mt-4 d-flex flex-column
                    align-items-center gap-2">

            <small class="text-secondary text-center">
                Menampilkan
                {{ $pesanan->firstItem() ?? 0 }}
                -
                {{ $pesanan->lastItem() ?? 0 }}
                dari
                {{ number_format(
                    $pesanan->total(), 0, ',', '.'
                ) }}
                pesanan
            </small>

            <div class="w-100 d-flex justify-content-center">
                {{ $pesanan->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
            </div>

        </div>
    @endif

</main>

</body>
</html>
