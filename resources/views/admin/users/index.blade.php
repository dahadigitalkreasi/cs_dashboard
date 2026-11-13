<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Manajemen Pengguna - CS Meta Ads</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            color: #1e293b;
        }

        .navbar {
            background: #111827;
        }

        .page-card {
            border: 1px solid #e8edf5;
            border-radius: 16px;
            overflow: hidden;
        }

        .table thead th {
            background: #f8fafc;
            color: #64748b;
            font-size: 12px;
            padding: 14px;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 14px;
            font-size: 13px;
            vertical-align: middle;
        }

        .role-badge {
            border-radius: 8px;
            padding: 7px 10px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
        }

        .role-admin {
            background: #f5f3ff;
            color: #6d28d9;
        }

        .role-leader {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .role-cs {
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark py-3">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand fw-bold"
           href="{{ route('dashboard') }}">
            <i class="bi bi-grid-1x2-fill me-2"></i>
            CS META ADS
        </a>

        <a href="{{ route('dashboard') }}"
           class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Dashboard
        </a>
    </div>
</nav>

<main class="container-fluid px-3 px-lg-4 py-4">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">
            <i class="bi bi-people me-2"></i>
            Manajemen Pengguna
        </h3>

        <p class="text-secondary">
            Kelola akun Admin, Leader CS, dan Customer Service.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success m-3">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger m-3">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="card page-card shadow-sm mb-4">
        <div class="card-body p-4">

            <form method="GET"
                  action="{{ route('admin.users.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">
                            Cari Pengguna
                        </label>

                        <input
                            type="text"
                            name="cari"
                            class="form-control"
                            placeholder="Nama, username, email, nama CS..."
                            value="{{ request('cari') }}"
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">
                            Role
                        </label>

                        <select name="role" class="form-select">
                            <option value="">Semua Role</option>
                            <option value="admin"
                                @selected(request('role') === 'admin')>
                                Admin
                            </option>
                            <option value="leader_cs"
                                @selected(request('role') === 'leader_cs')>
                                Leader CS
                            </option>
                            <option value="cs"
                                @selected(request('role') === 'cs')>
                                CS
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a href="{{ route('admin.users.index') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <div class="card page-card shadow-sm">

        <div class="card-header bg-white p-4">
            <div class="d-flex flex-wrap align-items-center
                        justify-content-between gap-3">

                <h5 class="fw-bold mb-0">
                    <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                    Daftar Akun
                </h5>

                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary">
                        {{ $users->total() }} Pengguna
                    </span>

                    <a href="{{ route('admin.users.create') }}"
                    class="btn btn-primary btn-sm">
                        <i class="bi bi-person-plus me-1"></i>
                        Tambah Pengguna
                    </a>
                </div>

            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Nama CS</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                        <th>Status Akun</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        @php
                            $roleClass = match ($user->role) {
                                'admin' => 'role-admin',
                                'leader_cs' => 'role-leader',
                                default => 'role-cs',
                            };
                        @endphp

                        <tr>
                            <td>
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            <td class="fw-semibold">
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ $user->username ?? '-' }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>
                                <span class="role-badge {{ $roleClass }}">
                                    {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>

                            <td>
                                {{ $user->nama_cs ?? '-' }}
                            </td>

                            <td>
                                {{ $user->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>

                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-slash-circle me-1"></i>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">

                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Edit
                                    </a>

                                    @if($user->is_active && $user->id === auth()->id())

                                        <span class="text-secondary small">
                                            Akun sendiri
                                        </span>

                                    @else

                                        <form method="POST"
                                            action="{{ route('admin.users.toggle-status', $user->id) }}"
                                            onsubmit="return confirm(@js($user->is_active ? 'Nonaktifkan akun ini?' : 'Aktifkan kembali akun ini?'))">

                                            @csrf
                                            @method('PATCH')

                                            @if($user->is_active)

                                                <button type="submit"
                                                        class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-person-x me-1"></i>
                                                    Nonaktifkan
                                                </button>

                                            @else

                                                <button type="submit"
                                                        class="btn btn-outline-success btn-sm">
                                                    <i class="bi bi-person-check me-1"></i>
                                                    Aktifkan
                                                </button>

                                            @endif

                                        </form>

                                    @endif

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="9"
                                class="text-center py-5 text-secondary">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                Tidak ada pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>

    </div>

</main>

</body>
</html>
