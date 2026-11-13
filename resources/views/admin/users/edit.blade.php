<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Pengguna - CS Meta Ads</title>

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

        .form-card {
            border: 1px solid #e8edf5;
            border-radius: 16px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
        }

        .form-control, .form-select {
            border-radius: 9px;
            padding: 11px;
        }

        .section-icon {
            width: 48px;
            height: 48px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 23px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark py-3">
    <div class="container">
        <a href="{{ route('dashboard') }}"
           class="navbar-brand fw-bold">
            <i class="bi bi-grid-1x2-fill me-2"></i>
            CS META ADS
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="btn btn-outline-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Daftar Pengguna
        </a>
    </div>
</nav>

<main class="container py-4 py-md-5" style="max-width:850px;">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">
            <i class="bi bi-pencil-square me-2"></i>
            Edit Pengguna
        </h3>

        <p class="text-secondary">
            Perbarui informasi akun {{ $user->name }}.
        </p>
    </div>

    <div class="card form-card shadow-sm">
        <div class="card-body p-4 p-md-5">

            <div class="section-icon mb-3">
                <i class="bi bi-person-gear"></i>
            </div>

            <h5 class="fw-bold mb-4">Informasi Akun</h5>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Periksa data yang dimasukkan.
                </div>
            @endif

            <form method="POST"
                  action="{{ route('admin.users.update', $user->id) }}">

                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>

                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}"
                               required>

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Username</label>

                        <input type="text"
                               name="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username', $user->username) }}"
                               required>

                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Email</label>

                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}"
                               required>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Role Pengguna</label>

                        <select name="role"
                                id="role"
                                class="form-select @error('role') is-invalid @enderror"
                                required>

                            <option value="cs"
                                @selected(old('role', $user->role) === 'cs')>
                                CS
                            </option>

                            <option value="leader_cs"
                                @selected(old('role', $user->role) === 'leader_cs')>
                                Leader CS
                            </option>

                            <option value="admin"
                                @selected(old('role', $user->role) === 'admin')>
                                Admin
                            </option>
                        </select>

                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6" id="nama-cs-wrapper">
                        <label class="form-label">Nama CS</label>

                        <select name="nama_cs"
                                id="nama_cs"
                                class="form-select @error('nama_cs') is-invalid @enderror">

                            <option value="">Pilih Nama CS</option>

                            @foreach($daftarCs as $cs)
                                <option value="{{ $cs }}"
                                    @selected(old('nama_cs', $user->nama_cs) === $cs)>
                                    {{ $cs }}
                                </option>
                            @endforeach
                        </select>

                        <div class="form-text">
                            Sesuaikan dengan nama CS pada Excel.
                        </div>

                        @error('nama_cs')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <hr class="my-4">

                <h5 class="fw-bold mb-2">
                    <i class="bi bi-shield-lock me-2 text-primary"></i>
                    Reset Password
                </h5>

                <p class="text-secondary small">
                    Kosongkan kedua kolom jika tidak ingin
                    mengganti password pengguna.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Password Baru</label>

                        <input type="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               autocomplete="new-password"
                               minlength="8">

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Konfirmasi Password Baru
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               autocomplete="new-password"
                               minlength="8">
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-outline-secondary px-4">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4">
                        <i class="bi bi-floppy me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</main>

<script>
    const role = document.getElementById('role');
    const wrapper = document.getElementById('nama-cs-wrapper');
    const namaCs = document.getElementById('nama_cs');

    function updateNamaCs() {
        const isCs = role.value === 'cs';

        wrapper.style.display = isCs ? '' : 'none';
        namaCs.required = isCs;
        namaCs.disabled = !isCs;
    }

    role.addEventListener('change', updateNamaCs);
    updateNamaCs();
</script>

</body>
</html>
