
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\RingkasanController;

use App\Http\Controllers\Admin\ImportPesananController;
use App\Http\Controllers\Admin\ImportIklanController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Middleware\EnsureUserIsActive;

Route::get('/', function () {
    return redirect()->route(
        auth()->check() ? 'dashboard' : 'login'
    );
});

Route::middleware('guest')->group(function () {

    // Halaman Login
    Route::get('/login', [
        LoginController::class,
        'showLogin'
    ])->name('login');

    // Proses Login
    Route::post('/login', [
        LoginController::class,
        'login'
    ])
    ->middleware('throttle:5,1')
    ->name('login.submit');

});

Route::middleware([
    'auth',
    EnsureUserIsActive::class,
])->group(function () {

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('dashboard');

    Route::get('/pesanan', [
        PesananController::class,
        'index'
    ])->name('pesanan.index');

    Route::get('/pesanan/{pesanan}', [
        PesananController::class,
        'show'
    ])
    ->whereNumber('pesanan')
    ->name('pesanan.show');

    // Performa pribadi berdasarkan akun login
    Route::get('/ringkasan/saya', [
        RingkasanController::class,
        'saya'
    ])->name('ringkasan.saya');

    // Semua CS - Admin dan Leader CS
    Route::get('/ringkasan/semua-cs', [
        RingkasanController::class,
        'semuaCs'
    ])->name('ringkasan.semua-cs');

    Route::get('/admin/import', [
        ImportPesananController::class,
        'index'
    ])->name('admin.import.index');

    Route::post('/admin/import', [
        ImportPesananController::class,
        'store'
    ])->name('admin.import.store');

    Route::get('/admin/import-iklan', [
        ImportIklanController::class,
        'index'
    ])->name('admin.import-iklan.index');

    Route::post('/admin/import-iklan', [
        ImportIklanController::class,
        'store'
    ])->name('admin.import-iklan.store');

    // Daftar pengguna
    Route::get('/admin/users', [
        UserController::class,
        'index'
    ])->name('admin.users.index');

    // Form tambah pengguna
    Route::get('/admin/users/create', [
        UserController::class,
        'create'
    ])->name('admin.users.create');

    // Simpan pengguna baru
    Route::post('/admin/users', [
        UserController::class,
        'store'
    ])->name('admin.users.store');

    // Form edit pengguna
    Route::get('/admin/users/{user}/edit', [
        UserController::class,
        'edit'
    ])
    ->whereNumber('user')
    ->name('admin.users.edit');

    // Simpan perubahan pengguna
    Route::put('/admin/users/{user}', [
        UserController::class,
        'update'
    ])
    ->whereNumber('user')
    ->name('admin.users.update');

    // Aktifkan / nonaktifkan pengguna
    Route::patch('/admin/users/{user}/status', [
        UserController::class,
        'toggleStatus'
    ])
    ->whereNumber('user')
    ->name('admin.users.toggle-status');

    Route::post('/logout', [
        LoginController::class,
        'logout'
    ])->name('logout');

});
