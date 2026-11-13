
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Detail Pesanan - CS Meta Ads</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        :root {
            --bg: #f5f7fb;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e8edf5;
            --primary: #2563eb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: system-ui, -apple-system, sans-serif;
        }

        .navbar-custom {
            background: #111827;
            padding: 13px 0;
        }

        .navbar-brand {
            font-size: 17px;
            font-weight: 800;
        }

        .main-content {
            max-width: 1100px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 800;
        }

        /* KARTU */

        .detail-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px;
            height: 100%;
            min-width: 0;
            box-shadow: 0 4px 18px rgba(15,23,42,.035);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 750;
            margin-bottom: 18px;
        }

        .section-icon {
            width: 35px;
            height: 35px;
            background: #eff6ff;
            color: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        /* HEADER PESANAN */

        .buyer-name {
            font-size: 21px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .order-id {
            color: var(--muted);
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        .status-pill {
            display: inline-block;
            border-radius: 8px;
            padding: 7px 10px;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
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

        /* BARIS INFORMASI
           LABEL KIRI, DATA KANAN */

        .info-row {
            display: grid;
            grid-template-columns: minmax(0, 42%) minmax(0, 1fr);
            align-items: start;
            column-gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:first-child {
            padding-top: 0;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-label {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.6;
        }

        .info-value {
            font-size: 13px;
            font-weight: 650;
            text-align: right;
            line-height: 1.6;
            min-width: 0;
            overflow-wrap: anywhere;
            white-space: pre-line;
        }

        /* TEKS PANJANG */

        .sub-label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .text-block {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 10px;
            padding: 13px;
            font-size: 13px;
            line-height: 1.7;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        /* KEUANGAN */

        .finance-box {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 15px;
            height: 100%;
            min-width: 0;
        }

        .finance-label {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 7px;
        }

        .finance-value {
            font-weight: 800;
            font-size: 16px;
            overflow-wrap: anywhere;
        }

        .profit-positive {
            color: #059669;
        }

        .profit-negative {
            color: #dc2626;
        }

        /* MOBILE */

        @media (max-width: 575.98px) {

            .navbar-custom {
                padding: 11px 0;
            }

            .navbar-brand {
                font-size: 14px;
            }

            .navbar-back {
                font-size: 11px;
                padding: 7px 9px;
            }

            .main-content {
                padding-left: 13px;
                padding-right: 13px;
            }

            .page-title {
                font-size: 21px;
            }

            .detail-card {
                padding: 17px 15px;
                border-radius: 14px;
            }

            .section-title {
                font-size: 14px;
                margin-bottom: 16px;
            }

            .buyer-name {
                font-size: 19px;
            }

            .info-row {
                grid-template-columns: minmax(0, 43%) minmax(0, 1fr);
                column-gap: 10px;
                padding: 11px 0;
            }

            .info-label {
                font-size: 12px;
            }

            .info-value {
                font-size: 12px;
            }

            .finance-box {
                padding: 12px 10px;
                border-radius: 10px;
            }

            .finance-label {
                font-size: 11px;
            }

            .finance-value {
                font-size: 14px;
            }

            .text-block {
                font-size: 12px;
                padding: 12px;
            }
        }

        @media (max-width: 350px) {
            .navbar-brand {
                font-size: 12px;
            }

            .finance-value {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

@php
    $status = strtoupper(trim($pesanan->status ?? ''));

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

    $rupiah = fn ($angka) => $angka === null
        ? '-'
        : 'Rp ' . number_format(
            (float) $angka, 0, ',', '.'
        );

    $keuntungan = (float) ($pesanan->keuntungan ?? 0);
@endphp

<!-- NAVBAR -->

<nav class="navbar navbar-dark navbar-custom">
    <div class="container main-content d-flex
                align-items-center justify-content-between gap-2">

        <a href="{{ route('dashboard') }}"
           class="navbar-brand mb-0 text-white text-decoration-none">
            <i class="bi bi-grid-1x2-fill me-1"></i>
            CS META ADS
        </a>

        <a href="{{ route('pesanan.index') }}"
           class="btn btn-outline-light btn-sm navbar-back">
            <i class="bi bi-arrow-left me-1"></i>
            Daftar Pesanan
        </a>

    </div>
</nav>

<!-- CONTENT -->

<main class="container main-content py-4">

    <div class="mb-4">
        <h1 class="page-title mb-1">
            Detail Pesanan
        </h1>

        <p class="text-secondary small mb-0">
            Informasi lengkap pesanan pelanggan.
        </p>
    </div>

    <div class="row g-3">

        <!-- RINGKASAN PESANAN -->

        <div class="col-12">
            <div class="detail-card">

                <div class="d-flex flex-wrap justify-content-between
                            align-items-start gap-2">

                    <div class="flex-grow-1" style="min-width:0;">

                        <div class="sub-label mb-1">
                            Nama Pembeli
                        </div>

                        <div class="buyer-name">
                            {{ $pesanan->nama_pembeli ?? 'Tanpa Nama' }}
                        </div>

                        <div class="order-id mt-2">
                            <i class="bi bi-receipt me-1"></i>
                            {{ $pesanan->id_pesanan }}
                        </div>

                    </div>

                    <span class="status-pill {{ $statusClass }}">
                        {{ $pesanan->status ?? 'BELUM ADA STATUS' }}
                    </span>

                </div>

                <hr class="my-3">

                <div class="info-row">
                    <div class="info-label">
                        Tanggal Order
                    </div>

                    <div class="info-value">
                        {{ $pesanan->tgl_order?->format('d/m/Y H:i') ?? '-' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        Customer Service
                    </div>

                    <div class="info-value">
                        {{ $pesanan->cs ?? '-' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        Kategori Produk
                    </div>

                    <div class="info-value">
                        {{ $pesanan->kategori ?? '-' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        Jumlah (QTY)
                    </div>

                    <div class="info-value">
                        {{ $pesanan->qty ?? '-' }}
                    </div>
                </div>

            </div>
        </div>

        <!-- DATA PEMBELI -->

        <div class="col-12 col-lg-6">
            <div class="detail-card">

                <h2 class="section-title">
                    <span class="section-icon">
                        <i class="bi bi-person-vcard"></i>
                    </span>
                    Data Pembeli
                </h2>

                <div class="info-row">
                    <div class="info-label">
                        Nama Pembeli
                    </div>

                    <div class="info-value">
                        {{ $pesanan->nama_pembeli ?? '-' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        Nomor HP
                    </div>

                    <div class="info-value">
                        {{ $pesanan->no_hp ?? '-' }}
                    </div>
                </div>

                <div class="mt-3">
                    <div class="sub-label">
                        Alamat Lengkap
                    </div>

                    <div class="text-block">{{ $pesanan->alamat ?? '-' }}</div>
                </div>

            </div>
        </div>

        <!-- INFORMASI PESANAN -->

        <div class="col-12 col-lg-6">
            <div class="detail-card">

                <h2 class="section-title">
                    <span class="section-icon">
                        <i class="bi bi-box-seam"></i>
                    </span>
                    Informasi Pesanan
                </h2>

                <div class="sub-label">
                    Notes Produk
                </div>

                <div class="text-block">{{ $pesanan->notes ?? '-' }}</div>

            </div>
        </div>

        <!-- INFORMASI PENGIRIMAN -->

        <div class="col-12 col-lg-6">
            <div class="detail-card">

                <h2 class="section-title">
                    <span class="section-icon">
                        <i class="bi bi-truck"></i>
                    </span>
                    Informasi Pengiriman
                </h2>

                <div class="info-row">
                    <div class="info-label">
                        Status Pesanan
                    </div>

                    <div class="info-value">
                        {{ $pesanan->status ?? '-' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        Ekspedisi
                    </div>

                    <div class="info-value">
                        {{ $pesanan->ekspedisi ?? '-' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">
                        Nomor Resi
                    </div>

                    <div class="info-value">
                        {{ $pesanan->no_resi ?? '-' }}
                    </div>
                </div>

            </div>
        </div>

        <!-- INFORMASI PEMBAYARAN -->

        <div class="col-12 col-lg-6">
            <div class="detail-card">

                <h2 class="section-title">
                    <span class="section-icon">
                        <i class="bi bi-wallet2"></i>
                    </span>
                    Informasi Pembayaran
                </h2>

                <div class="info-row">
                    <div class="info-label">
                        Jenis Pembayaran
                    </div>

                    <div class="info-value">
                        {{ $pesanan->pembayaran ?? '-' }}
                    </div>
                </div>

            </div>
        </div>

        <!-- RINCIAN KEUANGAN -->

        <div class="col-12">
            <div class="detail-card">

                <h2 class="section-title">
                    <span class="section-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </span>
                    Rincian Keuangan
                </h2>

                <div class="row g-2 g-md-3">

                    @foreach([
                        'Harga' => $pesanan->harga,
                        'HPP' => $pesanan->hpp,
                        'Ongkir' => $pesanan->ongkir,
                        'Fee COD' => $pesanan->fee_cod,
                        'Iklan' => $pesanan->iklan,
                        'Keuntungan' => $pesanan->keuntungan,
                    ] as $label => $nominal)

                        <div class="col-6 col-lg-4">
                            <div class="finance-box">

                                <div class="finance-label">
                                    {{ $label }}
                                </div>

                                <div class="finance-value
                                    {{ $label === 'Keuntungan'
                                        ? ($keuntungan < 0
                                            ? 'profit-negative'
                                            : 'profit-positive')
                                        : ''
                                    }}">

                                    {{ $rupiah($nominal) }}

                                </div>

                            </div>
                        </div>

                    @endforeach

                </div>

            </div>
        </div>

    </div>

    <!-- KEMBALI -->

    <div class="mt-4">
        <a href="{{ route('pesanan.index') }}"
           class="btn btn-outline-secondary w-100">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Daftar Pesanan
        </a>
    </div>

</main>

</body>
</html>
