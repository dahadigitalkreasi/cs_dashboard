<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\IklanHarian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $sekarang = Carbon::now('Asia/Jakarta');

        $mulai = $sekarang->copy()
            ->startOfMonth();

        $akhir = $mulai->copy()
            ->addMonth();

        $queryPesanan = Pesanan::query()
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

        $queryIklan = IklanHarian::query()
            ->where(
                'tanggal',
                '>=',
                $mulai->toDateString()
            )
            ->where(
                'tanggal',
                '<',
                $akhir->toDateString()
            );

        if ($user->role === 'cs') {

            $namaCs = strtoupper(
                trim((string) ($user->nama_cs ?? ''))
            );

            if ($namaCs === '') {

                $queryPesanan->whereRaw('1 = 0');
                $queryIklan->whereRaw('1 = 0');

            } else {

                $queryPesanan->whereRaw(
                    'UPPER(TRIM(cs)) = ?',
                    [$namaCs]
                );

                $queryIklan->whereRaw(
                    'UPPER(TRIM(cs)) = ?',
                    [$namaCs]
                );
            }
        }

        $totalDikirim = (clone $queryPesanan)
            ->whereRaw("
                UPPER(TRIM(COALESCE(status, '')))
                IN ('DI KIRIM', 'DIKIRIM', 'KIRIM')
            ")
            ->count();

        $queryValid = (clone $queryPesanan)
            ->whereRaw("
                UPPER(TRIM(COALESCE(status, '')))
                NOT IN ('BATAL', 'DIBATALKAN')
            ");

        $order = $queryValid
            ->selectRaw('
                COUNT(*) AS total_pesanan,
                COALESCE(SUM(harga), 0) AS total_omzet,
                COALESCE(SUM(hpp), 0) AS total_hpp,
                COALESCE(SUM(ongkir), 0) AS total_ongkir,
                COALESCE(SUM(fee_cod), 0) AS total_fee_cod
            ')
            ->first();

        $iklan = $queryIklan
            ->selectRaw('
                COALESCE(SUM(spend_iklan), 0) AS total_iklan,
                COALESCE(SUM(lead_cs), 0) AS total_lead_cs,

                COUNT(*) AS total_baris_iklan,

                COALESCE(SUM(
                    CASE
                        WHEN spend_iklan IS NULL THEN 1
                        ELSE 0
                    END
                ), 0) AS spend_kosong
            ')
            ->first();

        $omzet = (float) ($order->total_omzet ?? 0);
        $hpp = (float) ($order->total_hpp ?? 0);
        $ongkir = (float) ($order->total_ongkir ?? 0);
        $feeCod = (float) ($order->total_fee_cod ?? 0);
        $biayaIklan = (float) ($iklan->total_iklan ?? 0);

        $keuntungan = $omzet
            - $hpp
            - $ongkir
            - $feeCod
            - $biayaIklan;

        $margin = $omzet != 0
            ? ($keuntungan / $omzet) * 100
            : null;

        $kpi = (object) [

            'total_pesanan' => (int) (
                $order->total_pesanan ?? 0
            ),

            'total_omzet' => $omzet,

            'total_dikirim' => $totalDikirim,

            'total_keuntungan' => $keuntungan,

            'total_iklan' => $biayaIklan,

            'total_hpp' => $hpp,

            'total_ongkir' => $ongkir,

            'total_fee_cod' => $feeCod,

            'margin' => $margin,

            'total_lead_cs' => (int) (
                $iklan->total_lead_cs ?? 0
            ),

            'spend_kosong' => (int) (
                $iklan->spend_kosong ?? 0
            ),

            'total_baris_iklan' => (int) (
                $iklan->total_baris_iklan ?? 0
            ),
        ];

        return view('dashboard', [
            'kpi' => $kpi,
            'periode' => $sekarang
                ->locale('id')
                ->translatedFormat('F Y'),
        ]);
    }
}
