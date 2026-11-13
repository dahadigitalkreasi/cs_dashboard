
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Performa Semua CS - CS Meta Ads</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            background: #f5f7fb;
            color: #1e293b;
            font-family: system-ui, sans-serif;
        }

        .topbar { background: #111827; }
        .main-wrap { max-width: 1150px; }

        .panel {
            background: white;
            border: 1px solid #e8edf5;
            border-radius: 16px;
            padding: 20px;
            min-width: 0;
        }

        .page-title {
            font-size: 24px;
            font-weight: 800;
        }

        .section-title {
            font-size: 16px;
            font-weight: 750;
        }

        .hero-profit {
            border: 1px solid #e8edf5;
            background: white;
            border-radius: 16px;
            padding: 23px;
        }

        .muted-label {
            color: #64748b;
            font-size: 12px;
        }

        .profit-big {
            font-size: clamp(25px, 5vw, 38px);
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .positive { color: #059669; }
        .negative { color: #dc2626; }

        .kpi-box {
            background: white;
            border: 1px solid #e8edf5;
            padding: 16px;
            border-radius: 13px;
            height: 100%;
        }

        .kpi-value {
            font-size: clamp(15px, 2.5vw, 21px);
            font-weight: 750;
            overflow-wrap: anywhere;
        }

        .cs-card {
            border: 1px solid #e8edf5;
            border-radius: 14px;
            background: white;
            padding: 18px;
            height: 100%;
            min-width: 0;
        }

        .cs-name {
            font-size: 17px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .cs-profit {
            font-size: 23px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .cs-details {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 8px;
            font-size: 12px;
            padding: 9px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .cs-details:last-child {
            border-bottom: 0;
        }

        .cs-details span:last-child {
            text-align: right;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .summary-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .summary-row:last-child { border: 0; }

        .summary-row strong {
            text-align: right;
            overflow-wrap: anywhere;
        }

        .form-select, .btn {
            min-height: 42px;
            border-radius: 10px;
        }

        @media (max-width: 575.98px) {
            .main-wrap { padding: 18px 13px 35px !important; }
            .navbar-brand { font-size: 14px; }
            .page-title { font-size: 21px; }
            .panel { padding: 16px; }
            .hero-profit { padding: 18px; }
            .profit-big { font-size: 28px; }
            .kpi-box { padding: 13px 11px; }
            .kpi-value { font-size: 15px; }
            .cs-card { padding: 16px; }
            .cs-profit { font-size: 21px; }
            .summary-row { font-size: 12px; }
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
        ? $keuntungan / $omzet * 100
        : null;
@endphp

<nav class="navbar navbar-dark topbar py-3">
    <div class="container main-wrap d-flex justify-content-between align-items-center gap-2">

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

<main class="container main-wrap py-4">

    <div class="mb-4">
        <h1 class="page-title mb-1">
            Performa Semua CS
        </h1>

        <p class="text-secondary small mb-0">
            Evaluasi keuntungan dan margin setiap Customer Service.
        </p>
    </div>

    <!-- FILTER BULANAN -->

    <div class="panel mb-3">
        <form method="GET" action="{{ route('ringkasan.semua-cs') }}">
            <div class="row g-2 align-items-end">

                <div class="col-6 col-md-4">
                    <label class="muted-label fw-semibold mb-2">
                        Bulan
                    </label>

                    <select class="form-select" name="bulan">
                        @foreach($namaBulan as $nomor => $nama)
                            <option value="{{ $nomor }}"
                                @selected($bulan === $nomor)>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-4">
                    <label class="muted-label fw-semibold mb-2">
                        Tahun
                    </label>

                    <select class="form-select" name="tahun">
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

    <!-- KEUNTUNGAN KESELURUHAN -->

    <div class="hero-profit mb-3">
        <div class="muted-label mb-2">
            Total Keuntungan Semua CS
        </div>

        <div class="profit-big {{ $keuntungan >= 0 ? 'positive' : 'negative' }}">
            {{ $rupiah($keuntungan) }}
        </div>

        <hr>

        <div class="d-flex justify-content-between align-items-center gap-2">
            <span class="fw-semibold">Margin Keuntungan</span>

            <span class="fs-4 fw-bold
                {{ $keuntungan >= 0 ? 'positive' : 'negative' }}">
                {{ $margin === null
                    ? '-'
                    : number_format($margin, 2, ',', '.') . '%' }}
            </span>
        </div>

        <p class="text-secondary small mt-3 mb-0">
            {{ $namaBulan[$bulan] }} {{ $tahun }}
        </p>
    </div>

    <!-- KPI KESELURUHAN -->

    <div class="row g-2 g-md-3 mb-4">

        @foreach([
            ['Omzet', $rupiah($total->total_omzet)],
            ['Biaya Iklan', $rupiah($total->total_iklan)],
            ['Order Valid', $angka($total->total_pesanan)],
            ['Total QTY', $angka($total->total_qty)],
        ] as $kpi)

            <div class="col-6 col-lg-3">
                <div class="kpi-box">
                    <div class="muted-label mb-2">
                        {{ $kpi[0] }}
                    </div>
                    <div class="kpi-value">
                        {{ $kpi[1] }}
                    </div>
                </div>
            </div>

        @endforeach

    </div>

    <!-- RANKING CS -->

    <h2 class="section-title mb-3">
        <i class="bi bi-trophy text-warning me-2"></i>
        Ranking Keuntungan CS
    </h2>

    <div class="row g-3 mb-4">

        @forelse($perCs as $cs)
            @php
                $csOmzet = (float) $cs->total_omzet;
                $csProfit = (float) $cs->total_keuntungan;

                $csMargin = $csOmzet != 0
                    ? ($csProfit / $csOmzet) * 100
                    : null;
            @endphp

            <div class="col-12 col-md-6 col-xl-4">
                <div class="cs-card">

                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                        <div class="cs-name">
                            {{ $cs->cs }}
                        </div>

                        <span class="badge bg-light text-dark border">
                            #{{ $loop->iteration }}
                        </span>
                    </div>

                    <div class="muted-label mb-1">
                        Keuntungan
                    </div>

                    <div class="cs-profit mb-3
                        {{ $csProfit >= 0 ? 'positive' : 'negative' }}">
                        {{ $rupiah($csProfit) }}
                    </div>

                    <div class="cs-details">
                        <span class="text-secondary">Margin</span>
                        <span class="{{ $csProfit >= 0 ? 'positive' : 'negative' }}">
                            {{ $csMargin === null
                                ? '-'
                                : number_format($csMargin, 2, ',', '.') . '%' }}
                        </span>
                    </div>

                    <div class="cs-details">
                        <span class="text-secondary">Omzet</span>
                        <span>{{ $rupiah($csOmzet) }}</span>
                    </div>

                    <div class="cs-details">
                        <span class="text-secondary">Biaya Iklan</span>
                        <span>{{ $rupiah($cs->total_iklan) }}</span>
                    </div>

                    <div class="cs-details">
                        <span class="text-secondary">Order Valid</span>
                        <span>{{ $angka($cs->total_pesanan) }}</span>
                    </div>

                    <div class="cs-details">
                        <span class="text-secondary">Total QTY</span>
                        <span>{{ $angka($cs->total_qty) }}</span>
                    </div>

                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="panel text-center text-secondary">
                    Belum ada data CS pada periode ini.
                </div>
            </div>
        @endforelse

    </div>

    <!-- STATUS DAN KATEGORI -->

    <div class="row g-3 mb-3">

        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <h2 class="section-title mb-3">
                    <i class="bi bi-truck text-primary me-2"></i>
                    Status Pesanan
                </h2>

                @forelse($perStatus as $item)
                    <div class="summary-row">
                        <span>
                            {{ $item->status ?? 'TANPA STATUS' }}
                        </span>

                        <strong>
                            {{ $angka($item->total_pesanan) }}
                        </strong>
                    </div>
                @empty
                    <p class="small text-secondary mb-0">
                        Belum ada data status.
                    </p>
                @endforelse
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="panel h-100">
                <h2 class="section-title mb-3">
                    <i class="bi bi-tags text-primary me-2"></i>
                    Kategori Produk
                </h2>

                @forelse($perKategori as $item)
                    <div class="summary-row">
                        <div>
                            <strong>
                                {{ $item->kategori ?? 'TANPA KATEGORI' }}
                            </strong>

                            <div class="muted-label mt-1">
                                {{ $angka($item->total_pesanan) }} pesanan
                                · {{ $rupiah($item->total_omzet) }}
                            </div>
                        </div>

                        <strong class="{{ (float) $item->total_keuntungan < 0 ? 'negative' : 'positive' }}">
                            {{ $rupiah($item->total_keuntungan) }}
                        </strong>
                    </div>
                @empty
                    <p class="small text-secondary mb-0">
                        Belum ada data kategori.
                    </p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- RINCIAN KEUANGAN -->

    <div class="panel">
        <h2 class="section-title mb-3">
            <i class="bi bi-receipt text-primary me-2"></i>
            Rincian Keuangan
        </h2>

        @foreach([
            'Omzet' => $total->total_omzet,
            'HPP' => $total->total_hpp,
            'Ongkir' => $total->total_ongkir,
            'Fee COD' => $total->total_fee_cod,
            'Iklan' => $total->total_iklan,
        ] as $label => $nilai)

            <div class="summary-row">
                <span class="text-secondary">{{ $label }}</span>
                <strong>{{ $rupiah($nilai) }}</strong>
            </div>

        @endforeach

        <div class="summary-row">
            <strong>Keuntungan</strong>
            <strong class="{{ $keuntungan >= 0 ? 'positive' : 'negative' }}">
                {{ $rupiah($keuntungan) }}
            </strong>
        </div>

        <div class="summary-row">
            <strong>Margin</strong>
            <strong class="{{ $keuntungan >= 0 ? 'positive' : 'negative' }}">
                {{ $margin === null
                    ? '-'
                    : number_format($margin, 2, ',', '.') . '%' }}
            </strong>
        </div>
    </div>

    <p class="text-secondary small mt-3">
        <i class="bi bi-info-circle me-1"></i>
        Perhitungan mengikuti tanggal order dan nilai keuntungan
        pada Excel. Pesanan batal dikecualikan dari KPI finansial.
    </p>

</main>

</body>
</html>
