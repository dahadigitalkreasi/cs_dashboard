<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    private function filterAkses($query)
    {
        $user = Auth::user();

        if (in_array(
            $user->role,
            ['admin', 'leader_cs'],
            true
        )) {
            return $query;
        }

        $namaCs = strtoupper(
            trim((string) ($user->nama_cs ?? ''))
        );

        if ($namaCs === '') {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereRaw(
            'UPPER(TRIM(cs)) = ?',
            [$namaCs]
        );
    }

    public function index(Request $request)
    {
        $sekarang = Carbon::now('Asia/Jakarta');

        $data = $request->validate([
            'cari' => [
                'nullable',
                'string',
                'max:100',
            ],
            'bulan' => [
                'nullable',
                'integer',
                'between:1,12',
            ],
            'tahun' => [
                'nullable',
                'integer',
                'between:2000,2100',
            ],
        ]);

        $bulan = (int) (
            $data['bulan'] ?? $sekarang->month
        );

        $tahun = (int) (
            $data['tahun'] ?? $sekarang->year
        );

        $mulai = Carbon::create(
            $tahun,
            $bulan,
            1,
            0,
            0,
            0,
            'Asia/Jakarta'
        );

        $akhir = $mulai->copy()->addMonth();

        $query = Pesanan::query()
            ->where(
                'tgl_order',
                '>=',
                $mulai->format('Y-m-d H:i:s')
            )
            ->where(
                'tgl_order',
                '<',
                $akhir->format('Y-m-d H:i:s')
            );

        $this->filterAkses($query);

        $cari = trim(
            (string) ($data['cari'] ?? '')
        );

        if ($cari !== '') {

            $query->where(function ($q) use ($cari) {

                $q->where(
                    'id_pesanan',
                    'like',
                    "%{$cari}%"
                )

                ->orWhere(
                    'nama_pembeli',
                    'like',
                    "%{$cari}%"
                )

                ->orWhere(
                    'no_hp',
                    'like',
                    "%{$cari}%"
                )

                ->orWhere(
                    'no_resi',
                    'like',
                    "%{$cari}%"
                )

                ->orWhere(
                    'kategori',
                    'like',
                    "%{$cari}%"
                );

            });
        }

        $pesanan = $query
            ->orderByDesc('tgl_order')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $queryTahun = Pesanan::query()
            ->whereNotNull('tgl_order');

        $this->filterAkses($queryTahun);

        $tahunTersedia = $queryTahun
            ->selectRaw(
                'YEAR(tgl_order) AS tahun'
            )
            ->distinct()
            ->pluck('tahun')
            ->map(
                fn ($value) => (int) $value
            )
            ->push($sekarang->year)
            ->push($tahun)
            ->unique()
            ->sortDesc()
            ->values();

        $namaBulan = [];

        for ($i = 1; $i <= 12; $i++) {

            $namaBulan[$i] = Carbon::create(
                2000,
                $i,
                1
            )
            ->locale('id')
            ->translatedFormat('F');
        }

        $isAdminAtauLeader = in_array(
            Auth::user()->role,
            ['admin', 'leader_cs'],
            true
        );

        return view('pesanan.index', compact(
            'pesanan',
            'bulan',
            'tahun',
            'tahunTersedia',
            'namaBulan',
            'cari',
            'isAdminAtauLeader'
        ));
    }

    public function show(Pesanan $pesanan)
    {
        $user = Auth::user();

        if (!in_array(
            $user->role,
            ['admin', 'leader_cs'],
            true
        )) {

            $namaCsLogin = strtoupper(
                trim((string) ($user->nama_cs ?? ''))
            );

            $namaCsPesanan = strtoupper(
                trim((string) ($pesanan->cs ?? ''))
            );

            abort_if(
                $namaCsLogin === '' ||
                $namaCsLogin !== $namaCsPesanan,
                403,
                'Anda tidak memiliki akses ke pesanan ini.'
            );
        }

        return view('pesanan.show', [
            'pesanan' => $pesanan,
        ]);
    }
}
