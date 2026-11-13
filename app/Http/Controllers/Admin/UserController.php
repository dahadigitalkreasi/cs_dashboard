<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Hanya Admin yang bisa mengakses
        abort_unless(
            auth()->user()->role === 'admin',
            403
        );

        $request->validate([
            'cari' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'in:admin,leader_cs,cs'],
        ]);

        $query = User::query();

        // Pencarian pengguna
        if ($request->filled('cari')) {
            $cari = $request->input('cari');

            $query->where(function ($q) use ($cari) {
                $q->where('name', 'like', "%{$cari}%")
                  ->orWhere('username', 'like', "%{$cari}%")
                  ->orWhere('email', 'like', "%{$cari}%")
                  ->orWhere('nama_cs', 'like', "%{$cari}%");
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        // Daftar nama CS dari database pesanan
        $daftarCs = \App\Models\Pesanan::query()
            ->whereNotNull('cs')
            ->where('cs', '!=', '')
            ->distinct()
            ->orderBy('cs')
            ->pluck('cs');

        return view('admin.users.index', compact(
            'users',
            'daftarCs'
        ));
    }


    public function create()
    {
        abort_unless(
            auth()->user()->role === 'admin',
            403
        );

        $daftarCs = Pesanan::query()
            ->whereNotNull('cs')
            ->where('cs', '!=', '')
            ->distinct()
            ->orderBy('cs')
            ->pluck('cs');

        return view('admin.users.create', compact('daftarCs'));
    }

    public function store(Request $request)
    {
        abort_unless(
            auth()->user()->role === 'admin',
            403
        );

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_.]+$/',
                'unique:users,username',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'role' => [
                'required',
                Rule::in(['admin', 'leader_cs', 'cs']),
            ],
            'nama_cs' => [
                Rule::requiredIf(
                    fn () => $request->input('role') === 'cs'
                ),
                'nullable',
                'string',
                Rule::in(
                    Pesanan::query()
                        ->whereNotNull('cs')
                        ->where('cs', '!=', '')
                        ->distinct()
                        ->pluck('cs')
                        ->all()
                ),
            ],
        ]);

        $user = new User();

        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role = $data['role'];

        $user->nama_cs = $data['role'] === 'cs'
            ? $data['nama_cs']
            : null;

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function edit(User $user)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $daftarCs = Pesanan::query()
            ->whereNotNull('cs')
            ->where('cs', '!=', '')
            ->distinct()
            ->orderBy('cs')
            ->pluck('cs');

        return view('admin.users.edit', compact(
            'user',
            'daftarCs'
        ));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $daftarCs = Pesanan::query()
            ->whereNotNull('cs')
            ->where('cs', '!=', '')
            ->distinct()
            ->pluck('cs')
            ->all();

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_.]+$/',
                Rule::unique('users', 'username')
                    ->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'role' => [
                'required',
                Rule::in(['admin', 'leader_cs', 'cs']),
            ],

            'nama_cs' => [
                Rule::requiredIf(
                    fn () => $request->input('role') === 'cs'
                ),
                'nullable',
                'string',
                Rule::in($daftarCs),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        // Jangan sampai sistem kehilangan Admin terakhir
        if (
            $user->role === 'admin' &&
            $data['role'] !== 'admin' &&
            User::where('role', 'admin')->count() <= 1
        ) {
            return back()
                ->withErrors([
                    'role' => 'Tidak dapat mengubah role Admin terakhir.',
                ])
                ->withInput();
        }

        // Update data akun
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->role = $data['role'];

        // Nama CS hanya untuk role CS
        $user->nama_cs = $data['role'] === 'cs'
            ? $data['nama_cs']
            : null;

        // Password hanya diubah jika diisi
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        abort_unless(
            auth()->user()->role === 'admin',
            403
        );

        // Admin tidak boleh menonaktifkan diri sendiri.
        if (
            $user->id === auth()->id() &&
            $user->is_active
        ) {
            return back()->with(
                'error',
                'Tidak dapat menonaktifkan akun sendiri.'
            );
        }

        return \Illuminate\Support\Facades\DB::transaction(
            function () use ($user) {

                // Kunci baris Admin agar pemeriksaan
                // Admin aktif terakhir aman dari request bersamaan.
                $adminAktif = User::query()
                    ->where('role', 'admin')
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->get();

                $target = User::query()
                    ->lockForUpdate()
                    ->findOrFail($user->id);

                if (
                    $target->role === 'admin' &&
                    $target->is_active &&
                    $adminAktif->count() <= 1
                ) {
                    return back()->with(
                        'error',
                        'Admin aktif terakhir tidak dapat dinonaktifkan.'
                    );
                }

                $target->is_active = !$target->is_active;
                $target->save();

                return redirect()
                    ->route('admin.users.index')
                    ->with(
                        'success',
                        $target->is_active
                            ? 'Akun berhasil diaktifkan.'
                            : 'Akun berhasil dinonaktifkan.'
                    );
            }
        );
    }
}
