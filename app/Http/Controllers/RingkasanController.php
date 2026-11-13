<?php

namespace App\Http\Controllers;

use App\Models\IklanHarian;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RingkasanController extends Controller
{
    private function periode(Request $request): array
    {
        $data = $request->validate([
            'bulan' => ['nullable', 'integer', 'between:1,12'],
            'tahun' => ['nullable', 'integer', 'between:2000,2100'],
        ]);

        $sekarang = Carbon::now('Asia/Jakarta');

        return [
            (int) ($data['bulan'] ?? $sekarang->month),
            (int) ($data['tahun'] ?? $sekarang->year),
        ];
    }

    private function pilihanPeriode(int $tahun): array
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $tahunPesanan = Pesanan::query()
            ->whereNotNull('tgl_order')
            ->selectRaw('YEAR(tgl_order) AS tahun')
            ->distinct()
            ->pluck('tahun');

        $tahunIklan = IklanHarian::query()
            ->whereNotNull('tanggal')
            ->selectRaw('YEAR(tanggal) AS tahun')
            ->distinct()
            ->pluck('tahun');

        $tahunTersedia = $tahunPesanan
            ->concat($tahunIklan)
            ->push(now('Asia/Jakarta')->year)
            ->push($tahun)
            ->map(fn ($value) => (int) $value)
            ->filter(fn ($value) => $value >= 2000)
            ->unique()
            ->sortDesc()
            ->values();

        return [$namaBulan, $tahunTersedia];
    }

    private function pesananBulanan(
        int $bulan,
        int $tahun
    ): Builder {

        $mulai = Carbon::create(
            $tahun, $bulan, 1, 0, 0, 0,
            'Asia/Jakarta'
        );

        $akhir = $mulai->copy()->addMonth();

        return Pesanan::query()
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
    }

    private function iklanBulanan(
        int $bulan,
        int $tahun
    ): Builder {

        $mulai = Carbon::create(
            $tahun, $bulan, 1, 0, 0, 0,
            'Asia/Jakarta'
        );

        $akhir = $mulai->copy()->addMonth();

        return IklanHarian::query()
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
    }

    private function valid(Builder $query): Builder
    {
        return (clone $query)->whereRaw("
            UPPER(TRIM(COALESCE(status, '')))
            NOT IN ('BATAL', 'DIBATALKAN')
        ");
    }

    private function agregatOrder(Builder $query): object
    {
        return (clone $query)
            ->selectRaw("
                COUNT(*) AS total_pesanan,

                COALESCE(SUM(qty), 0)
                    AS total_qty,

                COALESCE(SUM(harga), 0)
                    AS total_omzet,

                COALESCE(SUM(hpp), 0)
                    AS total_hpp,

                COALESCE(SUM(ongkir), 0)
                    AS total_ongkir,

                COALESCE(SUM(fee_cod), 0)
                    AS total_fee_cod
            ")
            ->first();
    }

    private function agregatIklan(Builder $query): object
    {
        return (clone $query)
            ->selectRaw("
                COUNT(*) AS total_baris,

                COALESCE(SUM(lead_cs), 0)
                    AS total_lead_cs,

                COALESCE(SUM(lead_meta), 0)
                    AS total_lead_meta,

                COALESCE(SUM(spend_iklan), 0)
                    AS total_spend,

                COALESCE(SUM(
                    CASE
                        WHEN spend_iklan IS NULL THEN 1
                        ELSE 0
                    END
                ), 0) AS spend_kosong,

                COALESCE(SUM(
                    CASE
                        WHEN lead_cs IS NULL THEN 1
                        ELSE 0
                    END
                ), 0) AS lead_kosong
            ")
            ->first();
    }

    private function gabungkan($order, $ads): object
    {
        $jumlahOrder = (int) (
            $order->total_pesanan ?? 0
        );

        $qty = (int) (
            $order->total_qty ?? 0
        );

        $omzet = (float) (
            $order->total_omzet ?? 0
        );

        $hpp = (float) (
            $order->total_hpp ?? 0
        );

        $ongkir = (float) (
            $order->total_ongkir ?? 0
        );

        $feeCod = (float) (
            $order->total_fee_cod ?? 0
        );

        $iklan = (float) (
            $ads->total_spend ?? 0
        );

        $leadCs = (int) (
            $ads->total_lead_cs ?? 0
        );

        $leadMeta = (int) (
            $ads->total_lead_meta ?? 0
        );

        $keuntunganSebelumIklan =
            $omzet - $hpp - $ongkir - $feeCod;

        $keuntungan =
            $keuntunganSebelumIklan - $iklan;

        $margin = $omzet != 0
            ? ($keuntungan / $omzet) * 100
            : null;

        $closingRate = $leadCs > 0
            ? ($jumlahOrder / $leadCs) * 100
            : null;

        $cpo = $jumlahOrder > 0
            ? $iklan / $jumlahOrder
            : null;

        $roas = $iklan > 0
            ? $omzet / $iklan
            : null;

        return (object) [
            'total_pesanan' => $jumlahOrder,
            'total_qty' => $qty,

            'total_omzet' => $omzet,
            'total_hpp' => $hpp,
            'total_ongkir' => $ongkir,
            'total_fee_cod' => $feeCod,

            'total_iklan' => $iklan,

            'keuntungan_sebelum_iklan' =>
                $keuntunganSebelumIklan,

            'total_keuntungan' => $keuntungan,

            'margin' => $margin,

            'total_lead_cs' => $leadCs,
            'total_lead_meta' => $leadMeta,

            'closing_rate' => $closingRate,
            'cpo' => $cpo,
            'roas' => $roas,

            'baris_iklan' => (int) (
                $ads->total_baris ?? 0
            ),

            'spend_kosong' => (int) (
                $ads->spend_kosong ?? 0
            ),

            'lead_kosong' => (int) (
                $ads->lead_kosong ?? 0
            ),
        ];
    }

    private function statusDanKategori(
        Builder $orders
    ): array {

        // Semua status termasuk batal
        $perStatus = (clone $orders)
            ->selectRaw("
                status,
                COUNT(*) AS total_pesanan
            ")
            ->groupBy('status')
            ->orderByDesc('total_pesanan')
            ->get();

        $perKategori = $this->valid($orders)
            ->selectRaw("
                kategori,

                COUNT(*) AS total_pesanan,

                COALESCE(SUM(qty), 0)
                    AS total_qty,

                COALESCE(SUM(harga), 0)
                    AS total_omzet,

                COALESCE(SUM(hpp), 0)
                    AS total_hpp,

                COALESCE(SUM(ongkir), 0)
                    AS total_ongkir,

                COALESCE(SUM(fee_cod), 0)
                    AS total_fee_cod,

                (
                    COALESCE(SUM(harga), 0)
                    - COALESCE(SUM(hpp), 0)
                    - COALESCE(SUM(ongkir), 0)
                    - COALESCE(SUM(fee_cod), 0)
                ) AS total_keuntungan
            ")
            ->groupBy('kategori')
            ->orderByDesc('total_pesanan')
            ->get();

        return [$perStatus, $perKategori];
    }

    public function semuaCs(Request $request)
    {
        abort_unless(
            in_array(
                auth()->user()->role,
                ['admin', 'leader_cs'],
                true
            ),
            403
        );

        [$bulan, $tahun] = $this->periode($request);

        [$namaBulan, $tahunTersedia] =
            $this->pilihanPeriode($tahun);

        $orders = $this->pesananBulanan(
            $bulan,
            $tahun
        );

        $ads = $this->iklanBulanan(
            $bulan,
            $tahun
        );

        $total = $this->gabungkan(
            $this->agregatOrder(
                $this->valid($orders)
            ),
            $this->agregatIklan($ads)
        );

        $totalSemuaPesanan = (clone $orders)
            ->count();

        $totalBatal = $totalSemuaPesanan
            - $total->total_pesanan;

        $ordersByCs = $this->valid($orders)
            ->whereNotNull('cs')
            ->whereRaw("TRIM(cs) <> ''")
            ->selectRaw("
                UPPER(TRIM(cs)) AS cs,

                COUNT(*) AS total_pesanan,

                COALESCE(SUM(qty), 0)
                    AS total_qty,

                COALESCE(SUM(harga), 0)
                    AS total_omzet,

                COALESCE(SUM(hpp), 0)
                    AS total_hpp,

                COALESCE(SUM(ongkir), 0)
                    AS total_ongkir,

                COALESCE(SUM(fee_cod), 0)
                    AS total_fee_cod
            ")
            ->groupByRaw('UPPER(TRIM(cs))')
            ->get()
            ->keyBy('cs');

        $adsByCs = (clone $ads)
            ->whereNotNull('cs')
            ->whereRaw("TRIM(cs) <> ''")
            ->selectRaw("
                UPPER(TRIM(cs)) AS cs,

                COUNT(*) AS total_baris,

                COALESCE(SUM(lead_cs), 0)
                    AS total_lead_cs,

                COALESCE(SUM(lead_meta), 0)
                    AS total_lead_meta,

                COALESCE(SUM(spend_iklan), 0)
                    AS total_spend,

                COALESCE(SUM(
                    CASE
                        WHEN spend_iklan IS NULL THEN 1
                        ELSE 0
                    END
                ), 0) AS spend_kosong,

                COALESCE(SUM(
                    CASE
                        WHEN lead_cs IS NULL THEN 1
                        ELSE 0
                    END
                ), 0) AS lead_kosong
            ")
            ->groupByRaw('UPPER(TRIM(cs))')
            ->get()
            ->keyBy('cs');

        $namaSemuaCs = $ordersByCs
            ->keys()
            ->merge($adsByCs->keys())
            ->unique()
            ->values();

        $perCs = $namaSemuaCs
            ->map(function ($cs) use (
                $ordersByCs,
                $adsByCs
            ) {

                $row = $this->gabungkan(
                    $ordersByCs->get($cs),
                    $adsByCs->get($cs)
                );

                $row->cs = $cs;

                return $row;
            })
            ->sortByDesc('total_keuntungan')
            ->values();

        [$perStatus, $perKategori] =
            $this->statusDanKategori($orders);

        return view('ringkasan.semua-cs', compact(
            'bulan',
            'tahun',
            'namaBulan',
            'tahunTersedia',
            'total',
            'totalSemuaPesanan',
            'totalBatal',
            'perCs',
            'perStatus',
            'perKategori'
        ));
    }

    public function saya(Request $request)
    {
        [$bulan, $tahun] = $this->periode($request);

        [$namaBulan, $tahunTersedia] =
            $this->pilihanPeriode($tahun);

        $user = auth()->user();

        $namaCs = strtoupper(
            trim((string) ($user->nama_cs ?? ''))
        );

        $orders = $this->pesananBulanan(
            $bulan,
            $tahun
        );

        $ads = $this->iklanBulanan(
            $bulan,
            $tahun
        );

        if ($namaCs === '') {

            $orders->whereRaw('1 = 0');
            $ads->whereRaw('1 = 0');

        } else {

            $orders->whereRaw(
                'UPPER(TRIM(cs)) = ?',
                [$namaCs]
            );

            $ads->whereRaw(
                'UPPER(TRIM(cs)) = ?',
                [$namaCs]
            );
        }

        $total = $this->gabungkan(
            $this->agregatOrder(
                $this->valid($orders)
            ),
            $this->agregatIklan($ads)
        );

        $totalSemuaPesanan = (clone $orders)
            ->count();

        $totalBatal = $totalSemuaPesanan
            - $total->total_pesanan;

        [$perStatus, $perKategori] =
            $this->statusDanKategori($orders);

        return view('ringkasan.saya', compact(
            'namaCs',
            'bulan',
            'tahun',
            'namaBulan',
            'tahunTersedia',
            'total',
            'totalSemuaPesanan',
            'totalBatal',
            'perStatus',
            'perKategori'
        ));
    }
}
