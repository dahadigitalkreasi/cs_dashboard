<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Import Excel - CS Meta Ads</title>

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
        }

        .upload-icon {
            width: 64px;
            height: 64px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
        }

        .upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 30px 20px;
            background: #f8fafc;
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark py-3">
    <div class="container">
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

<main class="container py-4 py-md-5">

    <div class="mb-4">
        <h3 class="fw-bold">
            <i class="bi bi-file-earmark-arrow-up me-2"></i>
            Import Pesanan Excel
        </h3>

        <p class="text-secondary">
            Tambahkan atau perbarui data pesanan melalui file Excel.
        </p>
    </div>

    <div class="card page-card shadow-sm">
        <div class="card-body p-4">

            <div class="upload-icon mb-3">
                <i class="bi bi-file-earmark-excel"></i>
            </div>

            <h5 class="fw-bold">Upload File Pesanan</h5>

            <p class="text-secondary">
                Pilih file Excel berformat .xlsx atau .xls.
            </p>

            @if(session('error'))
                <div class="alert alert-warning">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST"
                  action="{{ route('admin.import.store') }}"
                  enctype="multipart/form-data">

                @csrf

                <div class="upload-area mb-4">
                    <label for="file_excel"
                           class="form-label fw-semibold">
                        Pilih File Excel
                    </label>

                    <input
                        type="file"
                        id="file_excel"
                        name="file_excel"
                        class="form-control @error('file_excel') is-invalid @enderror"
                        accept=".xlsx,.xls"
                        required
                    >

                    @error('file_excel')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text mt-2">
                        Format yang didukung: XLSX dan XLS.
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-1"></i>
                    Fitur pemrosesan Excel akan diaktifkan
                    pada tahap berikutnya.
                </div>

                <button type="submit"
                        class="btn btn-primary px-4">
                    <i class="bi bi-upload me-2"></i>
                    Upload Excel
                </button>

            </form>

        </div>
    </div>

</main>

</body>
</html>
