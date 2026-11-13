
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard - CS Meta Ads</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>
        :root {
            --primary: #2563eb;
            --background: #f5f7fb;
            --text: #1e293b;
            --muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: var(--text);
            font-family: system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* NAVBAR */

        .navbar-custom {
            background: #111827;
            padding: 14px 0;
        }

        .brand {
            color: white;
            font-size: 19px;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
        }

        .brand:hover {
            color: white;
        }

        .user-info {
            color: #d1d5db;
            font-size: 13px;
            max-width: 170px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* CONTENT */

        .dashboard-container {
            max-width: 1250px;
            margin: auto;
            padding: 32px 20px 50px;
        }

        .page-title {
            font-size: 27px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .page-description {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.6;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e0e7ff;
            color: #3730a3;
            border-radius: 8px;
            padding: 7px 12px;
            font-size: 12px;
            font-weight: 700;
        }

        /* SECTION */

        .section-heading {
            font-size: 17px;
            font-weight: 750;
            margin-bottom: 0;
        }

        .periode-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: white;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 600;
        }

        /* KPI */

        .kpi-card {
            background: white;
            border: 1px solid #e8edf5;
            border-radius: 16px;
            padding: 22px;
            height: 100%;
            min-width: 0;
            box-shadow: 0 4px 18px rgba(15,23,42,.035);
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            font-size: 22px;
            margin-bottom: 17px;
        }

        .icon-blue {
            background: #eff6ff;
            color: #2563eb;
        }

        .icon-green {
            background: #ecfdf5;
            color: #059669;
        }

        .icon-purple {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .icon-orange {
            background: #fff7ed;
            color: #ea580c;
        }

        .kpi-label {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 7px;
        }

        .kpi-value {
            font-size: clamp(18px, 2vw, 25px);
            font-weight: 800;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        /* MENU */

        .menu-card {
            display: flex;
            flex-direction: column;
            background: white;
            border: 1px solid #e8edf5;
            border-radius: 16px;
            padding: 24px;
            height: 100%;
            color: var(--text);
            text-decoration: none;
            transition: .2s ease;
            box-shadow: 0 4px 18px rgba(15,23,42,.035);
        }

        .menu-card:hover {
            transform: translateY(-3px);
            border-color: #bfdbfe;
            box-shadow: 0 10px 28px rgba(15,23,42,.07);
            color: var(--text);
        }

        .menu-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 24px;
            margin-bottom: 18px;
        }

        .menu-title {
            font-size: 17px;
            font-weight: 750;
            margin-bottom: 8px;
        }

        .menu-description {
            font-size: 13px;
            line-height: 1.6;
            color: var(--muted);
            margin-bottom: 19px;
        }

        .menu-footer {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #64748b;
            font-size: 12px;
        }

        .menu-arrow {
            width: 31px;
            height: 31px;
            border-radius: 9px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        /* NOTIFIKASI */

        .import-alert {
            border-radius: 13px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        /* RESPONSIVE TABLET */

        @media (max-width: 991.98px) {
            .dashboard-container {
                padding: 26px 18px 40px;
            }

            .kpi-card {
                padding: 19px;
            }
        }

        /* RESPONSIVE MOBILE */

        @media (max-width: 575.98px) {

            .navbar-custom {
                padding: 12px 0;
            }

            .brand {
                font-size: 15px;
                letter-spacing: -.3px;
            }

            .brand i {
                margin-right: 4px !important;
            }

            .user-info {
                display: none;
            }

            .btn-logout {
                font-size: 12px;
                padding: 7px 10px;
                border-radius: 8px;
            }

            .dashboard-container {
                padding: 22px 14px 35px;
            }

            .page-title {
                font-size: 23px;
            }

            .page-description {
                font-size: 13px;
            }

            .role-badge {
                font-size: 11px;
            }

            .section-heading {
                font-size: 16px;
            }

            .periode-badge {
                font-size: 11px;
                padding: 7px 10px;
            }

            .kpi-card {
                padding: 15px 12px;
                border-radius: 13px;
            }

            .kpi-icon {
                width: 40px;
                height: 40px;
                font-size: 19px;
                border-radius: 11px;
                margin-bottom: 12px;
            }

            .kpi-label {
                font-size: 11px;
                margin-bottom: 6px;
            }

            .kpi-value {
                font-size: clamp(15px, 4.3vw, 20px);
                letter-spacing: -.4px;
            }

            .menu-card {
                padding: 19px;
                border-radius: 14px;
            }

            .menu-icon {
                width: 46px;
                height: 46px;
                font-size: 21px;
                margin-bottom: 14px;
            }

            .menu-title {
                font-size: 16px;
            }

            .menu-description {
                font-size: 13px;
                margin-bottom: 14px;
            }

            .import-alert {
                font-size: 13px;
            }
        }

        /* HP SANGAT KECIL */

        @media (max-width: 350px) {
            .brand {
                font-size: 13px;
            }

            .kpi-card {
                padding: 12px 10px;
            }

            .kpi-value {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

@php
    $user = auth()->user();

    $rupiah = fn ($nilai) =>
        'Rp ' . number_format(
            abs((float) ($nilai ?? 0)),
            0,
            ',',
            '.'
        );

    $omzet = (float) ($kpi->total_omzet ?? 0);
    $keuntungan = (float) ($kpi->total_keuntungan ?? 0);
@endphp

<!-- NAVBAR -->

<nav class="navbar-custom">
    <div class="container-fluid px-3 px-md-4">

        <div class="d-flex justify-content-between
                    align-items-center gap-2 w-100">

            <a href="{{ route('dashboard') }}"
               class="brand">
                <i class="bi bi-grid-1x2-fill me-2"></i>
                CS META ADS
            </a>

            <div class="d-flex align-items-center gap-2 gap-md-3">

                <span class="user-info">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ $user->name }}
                </span>

                <form method="POST"
                      action="{{ route('logout') }}"
                      class="m-0">
                    @csrf

                    <button type="submit"
                            class="btn btn-outline-light btn-sm btn-logout">

                        <i class="bi bi-box-arrow-right me-1"></i>
                        Logout

                    </button>
                </form>

            </div>
        </div>

    </div>
</nav>

<!-- CONTENT -->

<main class="dashboard-container">

    <!-- WELCOME -->

    <div class="mb-4">

        <h1 class="page-title">
            Dashboard
        </h1>

        <p class="page-description mb-3">
            Selamat datang,
            <strong>{{ $user->name }}</strong>.
            Pantau pesanan dan aktivitas penjualan melalui dashboard.
        </p>

        <span class="role-badge">
            <i class="bi bi-shield-check"></i>
            {{ strtoupper(str_replace('_', ' ', $user->role)) }}
        </span>

    </div>

    <!-- HASIL IMPORT -->

    @if(session('import_result'))

        @php
            $hasil = session('import_result');
        @endphp

        <div class="alert alert-{{ $hasil['success'] ? 'success' : 'warning' }}
                    alert-dismissible fade show import-alert mb-4"
             role="alert">

            <div class="fw-bold mb-2">
                <i class="bi bi-check-circle me-1"></i>
                {{ $hasil['message'] }}
            </div>

            <div class="small">
                <div>Ditambahkan: <strong>{{ $hasil['ditambahkan'] }}</strong></div>
                <div>Diperbarui: <strong>{{ $hasil['diperbarui'] }}</strong></div>
                <div>Tidak berubah: <strong>{{ $hasil['tidak_berubah'] }}</strong></div>
                <div>Gagal: <strong>{{ $hasil['gagal'] }}</strong></div>
            </div>

            @if(!empty($hasil['errors']))
                <ul class="small mt-2 mb-0">
                    @foreach(array_slice($hasil['errors'], 0, 10) as $error)
                        <li>
                            Baris {{ $error['baris'] }}:
                            {{ $error['pesan'] }}
                        </li>
                    @endforeach
                </ul>
            @endif

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
        </div>

    @endif

    <!-- KPI -->

    <section class="mb-4 mb-md-5">

        <div class="d-flex flex-wrap
                    justify-content-between align-items-center
                    gap-2 mb-3">

            <h2 class="section-heading">
                <i class="bi bi-speedometer2 text-primary me-2"></i>
                Ringkasan Penjualan
            </h2>

            <span class="periode-badge">
                <i class="bi bi-calendar3"></i>
                {{ $periode }}
            </span>
        </div>

        <div class="row g-2 g-md-3">

            <!-- TOTAL PESANAN -->
            <div class="col-6 col-xl-3">
                <div class="kpi-card">

                    <div class="kpi-icon icon-blue">
                        <i class="bi bi-bag-check"></i>
                    </div>

                    <div class="kpi-label">
                        Total Pesanan
                    </div>

                    <div class="kpi-value">
                        {{ number_format(
                            $kpi->total_pesanan ?? 0,
                            0, ',', '.'
                        ) }}
                    </div>

                </div>
            </div>

            <!-- OMZET -->
            <div class="col-6 col-xl-3">
                <div class="kpi-card">

                    <div class="kpi-icon icon-green">
                        <i class="bi bi-wallet2"></i>
                    </div>

                    <div class="kpi-label">
                        Total Omzet
                    </div>

                    <div class="kpi-value">
                        {{ $omzet < 0 ? '-' : '' }}{{ $rupiah($omzet) }}
                    </div>

                </div>
            </div>

            <!-- DIKIRIM -->
            <div class="col-6 col-xl-3">
                <div class="kpi-card">

                    <div class="kpi-icon icon-blue">
                        <i class="bi bi-truck"></i>
                    </div>

                    <div class="kpi-label">
                        Pesanan Dikirim
                    </div>

                    <div class="kpi-value">
                        {{ number_format(
                            $kpi->total_dikirim ?? 0,
                            0, ',', '.'
                        ) }}
                    </div>

                </div>
            </div>

            <!-- KEUNTUNGAN -->
            <div class="col-6 col-xl-3">
                <div class="kpi-card">

                    <div class="kpi-icon icon-purple">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>

                    <div class="kpi-label">
                        Keuntungan
                    </div>

                    <div class="kpi-value
                        {{ $keuntungan < 0 ? 'text-danger' : 'text-success' }}">

                        {{ $keuntungan < 0 ? '-' : '' }}{{ $rupiah($keuntungan) }}

                    </div>

                </div>
            </div>

        </div>

    </section>

    <!-- MENU UTAMA -->

    <section>

        <h2 class="section-heading mb-3">
            <i class="bi bi-grid text-primary me-2"></i>
            Menu Utama
        </h2>

        <div class="row g-3">

            <!-- DAFTAR PESANAN -->
            <div class="col-12 col-md-6">
                <a href="{{ route('pesanan.index') }}"
                   class="menu-card">

                    <div class="menu-icon icon-blue">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <h3 class="menu-title">
                        Daftar Semua Pesanan
                    </h3>

                    <p class="menu-description">
                        Lihat dan pantau seluruh pesanan dari semua CS.
                    </p>

                    <div class="menu-footer">
                        <span>
                            <i class="bi bi-box-arrow-up-right me-1"></i>
                            Buka halaman
                        </span>

                        <span class="menu-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>

                </a>
            </div>

            <!-- RINGKASAN SAYA -->
            <div class="col-12 col-md-6">
                <a href="{{ route('ringkasan.saya') }}"
                   class="menu-card">

                    <div class="menu-icon icon-green">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>

                    <h3 class="menu-title">
                        Ringkasan Pesanan Saya
                    </h3>

                    <p class="menu-description">
                        Statistik pesanan dan penjualan pribadi CS.
                    </p>

                    <div class="menu-footer">
                        <span>
                            <i class="bi bi-box-arrow-up-right me-1"></i>
                            Buka halaman
                        </span>

                        <span class="menu-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>

                </a>
            </div>

            <!-- RINGKASAN SEMUA CS -->
            @if(in_array($user->role, ['admin', 'leader_cs']))

                <div class="col-12 col-md-6">
                    <a href="{{ route('ringkasan.semua-cs') }}"
                       class="menu-card">

                        <div class="menu-icon icon-purple">
                            <i class="bi bi-people"></i>
                        </div>

                        <h3 class="menu-title">
                            Ringkasan Semua CS
                        </h3>

                        <p class="menu-description">
                            Analisis dan perbandingan performa seluruh CS.
                        </p>

                        <div class="menu-footer">
                            <span>
                                <i class="bi bi-graph-up me-1"></i>
                                Lihat ringkasan
                            </span>

                            <span class="menu-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>

                    </a>
                </div>

            @endif

            <!-- IMPORT EXCEL -->
            @if($user->role === 'admin')

                <div class="col-12 col-md-6">
                    <a href="{{ route('admin.import.index') }}"
                       class="menu-card">

                        <div class="menu-icon icon-orange">
                            <i class="bi bi-file-earmark-arrow-up"></i>
                        </div>

                        <h3 class="menu-title">
                            Import Pesanan Excel
                        </h3>

                        <p class="menu-description">
                            Tambahkan atau perbarui data pesanan melalui Excel.
                        </p>

                        <div class="menu-footer">
                            <span>
                                <i class="bi bi-upload me-1"></i>
                                Buka import
                            </span>

                            <span class="menu-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>

                    </a>
                </div>

            @endif

            <!-- MANAJEMEN PENGGUNA -->
            @if($user->role === 'admin')

                <div class="col-12 col-md-6">
                    <a href="{{ route('admin.users.index') }}"
                       class="menu-card">

                        <div class="menu-icon icon-purple">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <h3 class="menu-title">
                            Manajemen Pengguna
                        </h3>

                        <p class="menu-description">
                            Kelola akun Admin, Leader CS,
                            dan Customer Service.
                        </p>

                        <div class="menu-footer">
                            <span>
                                <i class="bi bi-shield-check me-1"></i>
                                Khusus Admin
                            </span>

                            <span class="menu-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>

                    </a>
                </div>

            @endif

            @if(auth()->user()->role === 'admin')
            <div class="col-12 col-md-6">
                <a href="{{ route('admin.import-iklan.index') }}"
                class="menu-card">

                    <div class="menu-icon icon-orange">
                        <i class="bi bi-megaphone"></i>
                    </div>

                    <h3 class="menu-title">
                        Import Iklan Harian
                    </h3>

                    <p class="menu-description">
                        Upload Excel biaya iklan dan lead harian CS.
                    </p>

                    <div class="menu-footer">
                        <span>
                            <i class="bi bi-upload me-1"></i>
                            Buka Import
                        </span>

                        <span class="menu-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>

                </a>
            </div>
        @endif

        </div>

    </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
