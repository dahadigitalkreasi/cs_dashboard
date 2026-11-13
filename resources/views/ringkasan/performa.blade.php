<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $semua ? 'Performa Semua CS' : 'Performa Saya' }} - CS Meta Ads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        *{box-sizing:border-box}body{background:#f5f7fb;color:#1e293b;font-family:system-ui,sans-serif}
        .topbar{background:#111827}.wrap{max-width:1100px}.panel,.hero,.mini,.cs-card{background:#fff;border:1px solid #e8edf5;border-radius:15px;min-width:0}
        .panel{padding:20px}.hero{padding:22px}.mini{padding:15px;height:100%}.cs-card{padding:17px;height:100%}
        .label{font-size:12px;color:#64748b}.big-profit{font-size:clamp(26px,5vw,38px);font-weight:800;overflow-wrap:anywhere}
        .big-margin{font-size:clamp(21px,4vw,27px);font-weight:800}.kpi-val{font-size:clamp(15px,2.5vw,20px);font-weight:750;overflow-wrap:anywhere}
        .positive{color:#059669}.negative{color:#dc2626}.heading{font-size:16px;font-weight:750}.section-row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;border-bottom:1px solid #f1f5f9;padding:12px 0;font-size:13px;align-items:start}
        .section-row:last-child{border-bottom:0;padding-bottom:0}.section-row>div:first-child{min-width:0;overflow-wrap:anywhere}.section-row strong{text-align:right;overflow-wrap:anywhere}
        .form-select,.btn{min-height:42px;border-radius:10px}
        @media(max-width:575px){.wrap{padding-left:13px!important;padding-right:13px!important}.navbar-brand{font-size:14px}.panel{padding:16px}.hero{padding:18px}.mini{padding:12px 10px}.kpi-val{font-size:15px}.cs-card{padding:15px}.section-row{font-size:12px}}
    </style>
</head>
<body>
@php
    $rupiah = fn ($v) => 'Rp ' . number_format((float) ($v ?? 0), 0, ',', '.');
    $angka = fn ($v) => number_format((float) ($v ?? 0), 0, ',', '.');
    $persen = fn ($v) => $v === null ? '-' : number_format((float) $v, 2, ',', '.') . '%';
    $warna = $total->total_keuntungan >= 0 ? 'positive' : 'negative';
@endphp
<nav class="navbar navbar-dark topbar py-3">
    <div class="container wrap d-flex justify-content-between align-items-center gap-2">
        <a href="{{ route('dashboard') }}" class="navbar-brand fw-bold">CS META ADS</a>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light"><i class="bi bi-arrow-left me-1"></i>Dashboard</a>
    </div>
</nav>
<main class="container wrap py-4">
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1">{{ $semua ? 'Performa Semua CS' : 'Performa Saya' }}</h1>
        <p class="text-secondary small mb-2">Keuntungan dan margin berdasarkan tanggal order.</p>
        @unless($semua)<span class="badge bg-primary px-3 py-2">{{ $namaCs ?: 'CS BELUM DIATUR' }}</span>@endunless
    </div>
    @if(!$semua && $namaCs === '')
        <div class="alert alert-warning small">Akun ini belum dikaitkan ke nama CS. Hubungi Admin.</div>
    @endif
    <div class="panel mb-3">
        <form method="GET" action="{{ $semua ? route('ringkasan.semua-cs') : route('ringkasan.saya') }}">
            <div class="row g-2 align-items-end">
                <div class="col-6 col-md-4">
                    <label class="form-label label fw-semibold" for="bulan">Bulan</label>
                    <select name="bulan" id="bulan" class="form-select">
                        @foreach($namaBulan as $n => $v)<option value="{{ $n }}" @selected($bulan === $n)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4">
                    <label class="form-label label fw-semibold" for="tahun">Tahun</label>
                    <select name="tahun" id="tahun" class="form-select">
                        @foreach($tahunTersedia as $v)<option value="{{ $v }}" @selected($tahun === $v)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4"><button class="btn btn-primary w-100" type="submit"><i class="bi bi-funnel me-1"></i>Terapkan</button></div>
            </div>
        </form>
    </div>

    @if($total->baris_iklan === 0 || $total->spend_kosong > 0 || $total->lead_kosong > 0)
        <div class="alert alert-warning small">
            <strong>Periksa kelengkapan data iklan.</strong>
            @if($total->baris_iklan === 0)
                Belum ada data iklan untuk periode ini.
            @else
                Terdapat {{ $angka($total->spend_kosong) }} baris spend kosong dan
                {{ $angka($total->lead_kosong) }} baris lead CS kosong.
                Kolom kosong belum tentu berarti nol.
            @endif
            Angka keuntungan dan closing rate masih bersifat sementara sampai data iklan lengkap.
        </div>
    @endif

    <div class="hero mb-3">
        <div class="label mb-2">{{ $semua ? 'Total Keuntungan Semua CS' : 'Total Keuntungan Bersih' }}</div>
        <div class="big-profit {{ $warna }}">{{ $rupiah($total->total_keuntungan) }}</div>
        <hr>
        <div class="d-flex justify-content-between align-items-center gap-2">
            <strong class="small">Margin Keuntungan</strong>
            <span class="big-margin {{ $warna }}">{{ $persen($total->margin) }}</span>
        </div>
        <div class="label mt-3">{{ $namaBulan[$bulan] }} {{ $tahun }}</div>
    </div>

    <div class="row g-2 g-md-3 mb-3">
        @foreach([
            ['Omzet', $rupiah($total->total_omzet)],
            ['Biaya Iklan', $rupiah($total->total_iklan)],
            ['Order Valid', $angka($total->total_pesanan)],
            ['Lead CS', $angka($total->total_lead_cs)],
            ['Closing Rate', $persen($total->closing_rate)],
            ['CPO', $total->cpo === null ? '-' : $rupiah($total->cpo)],
            ['ROAS', $total->roas === null ? '-' : number_format($total->roas, 2, ',', '.') . 'x'],
            ['Total QTY', $angka($total->total_qty)],
        ] as $kpi)
            <div class="col-6 col-lg-3"><div class="mini">
                <div class="label mb-2">{{ $kpi[0] }}</div>
                <div class="kpi-val">{{ $kpi[1] }}</div>
            </div></div>
        @endforeach
    </div>

    @if($semua)
        <h2 class="heading mt-4 mb-3"><i class="bi bi-trophy me-2 text-warning"></i>Ranking Keuntungan CS</h2>
        <div class="row g-3 mb-4">
            @forelse($perCs as $cs)
                <div class="col-12 col-md-6 col-xl-4"><div class="cs-card">
                    <div class="d-flex justify-content-between gap-2 align-items-center mb-2">
                        <strong>{{ $cs->cs }}</strong>
                        <span class="badge bg-light text-dark border">#{{ $loop->iteration }}</span>
                    </div>
                    <div class="label">Keuntungan</div>
                    <div class="fs-4 fw-bold mb-2 {{ $cs->total_keuntungan >= 0 ? 'positive' : 'negative' }}">{{ $rupiah($cs->total_keuntungan) }}</div>
                    @foreach([
                        ['Margin', $persen($cs->margin)],
                        ['Omzet', $rupiah($cs->total_omzet)],
                        ['Biaya Iklan', $rupiah($cs->total_iklan)],
                        ['Order Valid', $angka($cs->total_pesanan)],
                        ['Lead CS', $angka($cs->total_lead_cs)],
                        ['Closing Rate', $persen($cs->closing_rate)],
                    ] as $line)
                        <div class="section-row"><div class="text-secondary">{{ $line[0] }}</div><strong>{{ $line[1] }}</strong></div>
                    @endforeach
                    @if($cs->spend_kosong || $cs->lead_kosong)
                        <div class="text-warning-emphasis small mt-2"><i class="bi bi-exclamation-triangle me-1"></i>Data iklan belum lengkap</div>
                    @endif
                </div></div>
            @empty
                <div class="col-12"><div class="panel text-secondary small">Belum ada data CS.</div></div>
            @endforelse
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-6"><div class="panel h-100">
            <h2 class="heading mb-3"><i class="bi bi-truck text-primary me-2"></i>Status Pesanan</h2>
            @forelse($perStatus as $item)
                <div class="section-row"><div>{{ $item->status ?: 'TANPA STATUS' }}</div><strong>{{ $angka($item->total_pesanan) }}</strong></div>
            @empty
                <div class="text-secondary small">Belum ada status pesanan.</div>
            @endforelse
        </div></div>
        <div class="col-12 col-lg-6"><div class="panel h-100">
            <h2 class="heading mb-3"><i class="bi bi-tags text-primary me-2"></i>Kategori Produk</h2>
            @forelse($perKategori as $item)
                <div class="section-row">
                    <div><strong>{{ $item->kategori ?: 'TANPA KATEGORI' }}</strong>
                        <div class="label mt-1">{{ $angka($item->total_pesanan) }} order · {{ $angka($item->total_qty) }} pcs</div>
                    </div>
                    <strong>{{ $rupiah($item->total_omzet) }}</strong>
                </div>
            @empty
                <div class="text-secondary small">Belum ada kategori produk.</div>
            @endforelse
        </div></div>
    </div>
    <div class="panel">
        <h2 class="heading mb-3"><i class="bi bi-receipt text-primary me-2"></i>Rincian Keuangan</h2>
        @foreach([
            'Omzet' => $total->total_omzet,
            'HPP' => $total->total_hpp,
            'Ongkir' => $total->total_ongkir,
            'Fee COD' => $total->total_fee_cod,
            'Iklan (semua produk)' => $total->total_iklan,
        ] as $label => $value)
            <div class="section-row"><div class="text-secondary">{{ $label }}</div><strong>{{ $rupiah($value) }}</strong></div>
        @endforeach
        <div class="section-row"><div class="fw-bold">Keuntungan Bersih</div><strong class="{{ $warna }}">{{ $rupiah($total->total_keuntungan) }}</strong></div>
        <div class="section-row"><div class="fw-bold">Margin</div><strong class="{{ $warna }}">{{ $persen($total->margin) }}</strong></div>
    </div>
    <p class="text-secondary small mt-3">
        <i class="bi bi-info-circle me-1"></i>
        Keuangan dihitung ulang dari omzet - HPP - ongkir - fee COD - total iklan harian, bukan dari kolom keuntungan Excel.
        Status mencakup pesanan batal; kategori hanya menampilkan jumlah dan omzet order valid.
    </p>
</main>
</body>
</html>
