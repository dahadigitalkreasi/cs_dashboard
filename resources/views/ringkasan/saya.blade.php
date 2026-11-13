
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Performa Saya - CS Meta Ads</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --bg: #f5f7fb;
            --text: #172033;
            --muted: #64748b;
            --border: #e8edf5;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: system-ui, sans-serif;
        }

        .topbar { background: #111827; }

        .main-wrap { max-width: 1080px; }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            min-width: 0;
        }

        .page-title {
            font-size: 24px;
            font-weight: 800;
        }

        .section-title {
            font-size: 15px;
            font-weight: 750;
            margin: 0;
        }

        .section-title i { color: #2563eb; }

        .filter-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-select, .btn {
            border-radius: 10px;
            min-height: 42px;
        }

        .hero-profit {
            border-radius: 16px;
            background: #fff;
            border: 1px solid var(--border);
            padding: 24px;
        }

        .muted-label {
            color: var(--muted);
            font-size: 12px;
        }

        .profit-value {
            font-size: clamp(25px, 5vw, 38px);
            font-weight: 800;
            letter-spacing: -1px;
            overflow-wrap: anywhere;
        }

        .margin-value {
            font-size: 26px;
            font-weight: 800;
        }

        .positive { color: #059669; }
        .negative { color: #dc2626; }

        .mini-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            height: 100%;
            min-width: 0;
        }

        .mini-value {
            font-size: clamp(15px, 2.5vw, 21px);
            font-weight: 750;
            overflow-wrap: anywhere;
            line-height: 1.35;
        }

        .data-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: start;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .data-row:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .data-name {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .data-number {
            text-align: right;
            font-weight: 700;
            white-space: nowrap;
        }

        .category-meta {
            color: var(--muted);
            font-size: 12px;
            margin-top: 4px;
            overflow-wrap: anywhere;
        }

        @media (max-width: 575.98px) {
            .main-wrap { padding: 18px 13px 35px !important; }
            .navbar-brand { font-size: 14px; }
            .page-title { font-size: 21px; }
            .panel { padding: 16px; border-radius: 13px; }
            .hero-profit { padding: 18px; }
            .profit-value { font-size: 28px; }
            .margin-value { font-size: 23px; }
            .mini-card { padding: 13px 11px; }
            .mini-value { font-size: 15px; }
            .data-row { font-size: 12px; }
            .form-select { font-size: 13px; }
        }
    </style>
</head>
<body>

@php
    $rupiah = fn ($nilai) => 'Rp ' . number_format(
        (float) ($nilai ?? 0), 0, ',', '.'
    );

    $angka = fn ($nilai) => number_format(
        (float) ($nilai ?? 0), 0, ',', '.'
    );

    $omzet = (float) ($total->total_omzet ?? 0);
    $keuntungan = (float) ($total->total_keuntungan ?? 0);

    $margin = $omzet != 0
        ? ($keuntungan / $omzet) * 100
        : null;

    $isProfit = $keuntungan >= 0;
@endphp

<nav class="navbar navbar-dark topbar py-3">
    <div class="container main-wrap d-flex justify-content-between align-items-center gap-2">
        <a href="{{ route('dashboard') }}"
           class="navbar-brand fw-bold mb-0">
            <i class="bi bi-grid-1x2-fill me-1"></i>
            CS META ADS
        </a>

        <a href="{{ route('dashboard') }}"
           class="btn btn-sm btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i>
            Dashboard
        </a>
    </div>
</nav>

<main class="container main-wrap py-4">

    <div class="mb-4">
        <h1 class="page-title mb-1">Performa Saya</h1>
        <p class="text-secondary small mb-2">
            Keuntungan dan performa penjualan bulanan.
        </p>

        <span class="badge bg-primary px-3 py-2">
            <i class="bi bi-person-check me-1"></i>
            {{ $namaCs !== '' ? $namaCs : 'CS BELUM DIATUR' }}
        </span>
    </div>

    @if($namaCs === '')
        <div class="alert alert-warning small">
            Akun ini belum dihubungkan dengan nama CS.
            Hubungi Admin untuk mengatur nama CS.
        </div>
    @endif

    <!-- FILTER BULAN -->

    <div class="panel mb-3">
        <form method="GET" action="{{ route('ringkasan.saya') }}">
            <div class="row g-2 align-items-end">

                <div class="col-6 col-md-4">
                    <label class="filter-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        @foreach($namaBulan as $nomor => $nama)
                            <option value="{{ $nomor }}"
                                @selected($bulan === $nomor)>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-4">
                    <label class="filter-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        @foreach($tahunTersedia as $pilihanTahun)
                            <option value="{{ $pilihanTahun }}"
                                @selected($tahun === $pilihanTahun)>
                                {{ $pilihanTahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-4">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bi bi-funnel me-1"></i>
                        Terapkan
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- KEUNTUNGAN DAN MARGIN UTAMA -->

    <div class="hero-profit mb-3">
        <div class="muted-label mb-2">
            Total Keuntungan Bersih
        </div>

        <div class="profit-value {{ $isProfit ? 'positive' : 'negative' }}">
            {{ $rupiah($keuntungan) }}
        </div>

        <hr>

        <div class="d-flex justify-content-between align-items-center gap-2">
            <div class="fw-semibold small">
                Margin Keuntungan
            </div>

            <div class="margin-value
                {{ $isProfit ? 'positive' : 'negative' }}">
                {{ $margin === null
                    ? '-'
                    : number_format($margin, 2, ',', '.') . '%' }}
            </div>
        </div>

        <div class="progress mt-3" style="height:8px;">
            <div class="progress-bar {{ $isProfit ? 'bg-success' : 'bg-danger' }}"
                 role="progressbar"
                 style="width: {{ min(abs($margin ?? 0), 100) }}%"
                 aria-valuenow="{{ min(abs($margin ?? 0), 100) }}"
                 aria-valuemin="0"
                 aria-valuemax="100">
            </div>
        </div>

        <div class="text-secondary small mt-3">
            {{ $namaBulan[$bulan] }} {{ $tahun }}
        </div>
    </div>

    <!-- KPI PENDUKUNG -->

    <div class="row g-2 g-md-3 mb-3">

        @foreach([
            ['label' => 'Total Omzet', 'value' => $rupiah($total->total_omzet), 'icon' => 'bi-wallet2'],
            ['label' => 'Biaya Iklan', 'value' => $rupiah($total->total_iklan), 'icon' => 'bi-megaphone'],
            ['label' => 'Order Valid', 'value' => $angka($total->total_pesanan), 'icon' => 'bi-bag-check'],
            ['label' => 'Total QTY', 'value' => $angka($total->total_qty), 'icon' => 'bi-box-seam'],
        ] as $kpi)

            <div class="col-6 col-lg-3">
                <div class="mini-card">
                    <div class="text-primary mb-2">
                        <i class="bi {{ $kpi['icon'] }} fs-5"></i>
                    </div>
                    <div class="muted-label mb-1">
                        {{ $kpi['label'] }}
                    </div>
                    <div class="mini-value">
                        {{ $kpi['value'] }}
                    </div>
                </div>
            </div>

        @endforeach

    </div>

    <!-- STATUS DAN KATEGORI -->

    <div class="row g-3 mb-3">

        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <h2 class="section-title mb-3">
                    <i class="bi bi-truck me-2"></i>
                    Status Pesanan
                </h2>

                @forelse($perStatus as $item)
                    <div class="data-row">
                        <div class="data-name">
                            {{ $item->status ?? 'TANPA STATUS' }}
                        </div>
                        <div class="data-number">
                            {{ $angka($item->total_pesanan) }}
                        </div>
                    </div>
                @empty
                    <p class="text-secondary small mb-0">
                        Belum ada pesanan.
                    </p>
                @endforelse
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <h2 class="section-title mb-3">
                    <i class="bi bi-tags me-2"></i>
                    Kategori Produk
                </h2>

                @forelse($perKategori as $item)
                    <div class="data-row">
                        <div class="data-name">
                            <strong>
                                {{ $item->kategori ?? 'TANPA KATEGORI' }}
                            </strong>
                            <div class="category-meta">
                                {{ $angka($item->total_pesanan) }} pesanan
                                · {{ $rupiah($item->total_omzet) }}
                            </div>
                        </div>

                        <div class="data-number
                            {{ (float) $item->total_keuntungan < 0
                                ? 'negative' : 'positive' }}">
                            {{ $rupiah($item->total_keuntungan) }}
                        </div>
                    </div>
                @empty
                    <p class="text-secondary small mb-0">
                        Belum ada kategori produk.
                    </p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- RINCIAN KEUANGAN -->

    <div class="panel">
        <h2 class="section-title mb-3">
            <i class="bi bi-receipt me-2"></i>
            Rincian Keuangan
        </h2>

        @foreach([
            'Omzet' => $total->total_omzet,
            'HPP' => $total->total_hpp,
            'Ongkir' => $total->total_ongkir,
            'Fee COD' => $total->total_fee_cod,
            'Iklan' => $total->total_iklan,
        ] as $label => $nilai)

            <div class="data-row">
                <div class="data-name text-secondary">
                    {{ $label }}
                </div>
                <div class="data-number">
                    {{ $rupiah($nilai) }}
                </div>
            </div>

        @endforeach

        <div class="data-row">
            <div class="data-name fw-bold">
                Keuntungan
            </div>
            <div class="data-number {{ $isProfit ? 'positive' : 'negative' }}">
                {{ $rupiah($keuntungan) }}
            </div>
        </div>

        <div class="data-row">
            <div class="data-name fw-bold">
                Margin
            </div>
            <div class="data-number {{ $isProfit ? 'positive' : 'negative' }}">
                {{ $margin === null
                    ? '-'
                    : number_format($margin, 2, ',', '.') . '%' }}
            </div>
        </div>

    </div>

    <p class="text-secondary small mt-3 mb-0">
        <i class="bi bi-info-circle me-1"></i>
        Perhitungan berdasarkan tanggal order.
        Keuntungan mengikuti nominal yang diimpor dari Excel.
        Distribusi status termasuk pesanan batal.
    </p>

</main>
</body>
</html>
