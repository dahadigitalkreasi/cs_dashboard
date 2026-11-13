<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Import Iklan Harian - CS Meta Ads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body{background:#f5f7fb;color:#1e293b;font-family:system-ui,sans-serif}
        .topbar{background:#111827}.wrap{max-width:720px}
        .panel{background:white;border:1px solid #e7ecf4;border-radius:16px;padding:24px}
        .form-control,.btn{min-height:46px;border-radius:10px}
        @media(max-width:575px){.panel{padding:17px}.navbar-brand{font-size:14px}}
    </style>
</head>
<body>
<nav class="navbar navbar-dark topbar py-3">
    <div class="container wrap d-flex justify-content-between align-items-center gap-2">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">CS META ADS</a>
        <a class="btn btn-outline-light btn-sm" href="{{ route('dashboard') }}">Dashboard</a>
    </div>
</nav>
<main class="container wrap py-4">
    <h1 class="h4 fw-bold mb-1">Import Iklan Harian</h1>
    <p class="text-secondary small mb-4">Upload Excel iklan per tanggal, CS, dan produk. Baris tanpa order tetap tersimpan.</p>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    @if(session('import_iklan_result'))
        @php $r = session('import_iklan_result'); @endphp
        <div class="alert alert-{{ $r['gagal'] ? 'warning' : 'success' }}">
            <strong>Import selesai.</strong>
            <div class="small mt-2">
                Ditambahkan: {{ $r['ditambahkan'] }} ·
                Diperbarui: {{ $r['diperbarui'] }} ·
                Tidak berubah: {{ $r['tidak_berubah'] }} ·
                Dilewati: {{ $r['dilewati'] }} ·
                Gagal: {{ $r['gagal'] }}
            </div>
            @if(count($r['errors']))
                <ul class="small mt-2 mb-0">
                    @foreach($r['errors'] as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <a href="{{ route('ringkasan.semua-cs') }}" class="btn btn-sm btn-outline-success mt-3">Lihat Performa CS</a>
        </div>
    @endif

    <div class="panel shadow-sm">
        <form method="POST" action="{{ route('admin.import-iklan.store') }}" enctype="multipart/form-data">
            @csrf
            <label for="file_excel" class="form-label fw-semibold">File Excel Iklan</label>
            <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx,.xls" required>
            <div class="form-text">Maksimal 10 MB. Format .xlsx atau .xls.</div>
            <button type="submit" class="btn btn-primary w-100 mt-4">
                <i class="bi bi-upload me-1"></i> Import Iklan
            </button>
        </form>
    </div>

    <div class="panel mt-3 small">
        <strong>Header kolom Excel (baris pertama tabel):</strong>
        <div class="mt-2 text-secondary">TANGGAL · NAMA · PRODUK · LEAD META · LEAD CS · SPEND IKLAN</div>
        <hr>
        <p class="mb-1">Contoh: <strong>02/11/2026</strong> · AINUN · BANNER KAIN · (kosong) · 16 · Rp59.791</p>
        <p class="mb-0 text-secondary">Spend kosong disimpan NULL (belum dilaporkan), sedangkan Rp0 disimpan 0. Baris total atau baris tanpa tanggal dilewati. Import ulang memperbarui data pada kombinasi tanggal + CS + produk.</p>
    </div>
</main>
</body>
</html>
