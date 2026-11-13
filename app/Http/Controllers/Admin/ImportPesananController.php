<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class ImportPesananController extends Controller
{
    // ==========================================
    // HALAMAN IMPORT EXCEL
    // ==========================================

    public function index()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.import');
    }

    // ==========================================
    // KONVERSI TANGGAL EXCEL
    // ==========================================
    private function tanggal($value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        // Jika Excel mengirim tanggal sebagai angka serial
        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject(
                (float) $value
            )->format('Y-m-d H:i:s');
        }

        $value = trim((string) $value);

        // Format Excel Indonesia:
        // 07/11/2026 21.06.46
        // 07/11/2026 21:06:46
        // 07/11/2026
        if (preg_match(
            '/^(\d{1,2})\/(\d{1,2})\/(\d{4})(?:\s+(\d{1,2})[.:](\d{2})[.:](\d{2}))?$/',
            $value,
            $matches
        )) {
            $hari = (int) $matches[1];
            $bulan = (int) $matches[2];
            $tahun = (int) $matches[3];

            $jam = (int) ($matches[4] ?? 0);
            $menit = (int) ($matches[5] ?? 0);
            $detik = (int) ($matches[6] ?? 0);

            if (
                !checkdate($bulan, $hari, $tahun) ||
                $jam > 23 ||
                $menit > 59 ||
                $detik > 59
            ) {
                throw new \InvalidArgumentException(
                    'Tanggal Excel tidak valid: ' . $value
                );
            }

            return sprintf(
                '%04d-%02d-%02d %02d:%02d:%02d',
                $tahun,
                $bulan,
                $hari,
                $jam,
                $menit,
                $detik
            );
        }

        // Format standar database: 2026-11-07 21:06:46
        if (preg_match(
            '/^\d{4}-\d{2}-\d{2}(?:\s+\d{2}:\d{2}:\d{2})?$/',
            $value
        )) {
            return Carbon::parse($value)
                ->format('Y-m-d H:i:s');
        }

        throw new \InvalidArgumentException(
            'Format tanggal tidak dikenali: ' . $value
        );
    }
    // ==========================================
    // KONVERSI NILAI ANGKA
    // ==========================================
    private function angka($value): ?float
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        // Angka asli dari Excel
        if (is_numeric($value)) {
            return (float) $value;
        }

        $nilai = trim((string) $value);

        // Format kosong pada Excel Accounting
        if (preg_match('/^(?:Rp\s*)?[-–—]$/iu', $nilai)) {
            return 0;
        }

        // Hilangkan simbol mata uang dan spasi
        $nilai = preg_replace('/^Rp\.?\s*/iu', '', $nilai);
        $nilai = preg_replace('/[\s\x{00A0}]/u', '', $nilai);

        // Format Indonesia: 1.250.000,50
        if (preg_match('/^-?\d{1,3}(?:\.\d{3})+(?:,\d+)?$/', $nilai)) {
            $nilai = str_replace('.', '', $nilai);
            $nilai = str_replace(',', '.', $nilai);
        }
        // Format desimal Indonesia: 12500,50
        elseif (preg_match('/^-?\d+,\d+$/', $nilai)) {
            $nilai = str_replace(',', '.', $nilai);
        }

        if (!is_numeric($nilai)) {
            throw new \InvalidArgumentException(
                'Nilai angka tidak valid: ' . (string) $value
            );
        }

        return (float) $nilai;
    }

    // ==========================================
    // KONVERSI NILAI TEKS
    // ==========================================

    private function teks($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    // ==========================================
    // PROSES IMPORT EXCEL
    // ==========================================

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $request->validate([
            'file_excel' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'extensions:xlsx,xls',
                'max:10240',
            ],
        ]);

        $path = null;
        $spreadsheet = null;

        try {
            // ==========================================
            // 1. SIMPAN FILE SEMENTARA
            // ==========================================

            $folder = storage_path('app/import-temp');

            File::ensureDirectoryExists($folder);

            $file = $request->file('file_excel');

            $filename = Str::uuid() . '.' .
                strtolower($file->getClientOriginalExtension());

            $uploaded = $file->move($folder, $filename);

            $path = $uploaded->getPathname();

            if (!is_file($path) || !is_readable($path)) {
                throw new \RuntimeException(
                    'File Excel tidak dapat dibaca.'
                );
            }

            // ==========================================
            // 2. BACA SHEET PESANAN
            // ==========================================

            $reader = IOFactory::createReaderForFile($path);

            $reader->setReadDataOnly(true);
            $reader->setLoadSheetsOnly(['PESANAN']);

            $spreadsheet = $reader->load($path);

            $sheet = $spreadsheet->getSheetByName('PESANAN');

            if (!$sheet) {
                throw new \RuntimeException(
                    'Sheet PESANAN tidak ditemukan.'
                );
            }

            $rows = $sheet->toArray(
                null,
                false,
                false,
                false
            );

            // ==========================================
            // 3. VALIDASI HEADER
            // ==========================================

            $headers = array_map(
                fn ($value) => strtoupper(
                    trim((string) ($value ?? ''))
                ),
                array_shift($rows) ?? []
            );

            $requiredHeaders = [
                'ID',
                'TGL CHAT MASUK',
                'TGL ORDER',
                'CS',
                'NAMA PEMBELI',
                'NO. HP',
                'ALAMAT',
                'NO RESI',
                'STATUS',
                'EKSPEDISI',
                'PEMBAYARAN',
                'KATEGORI',
                'QTY',
                'NOTES',
                'NOMINAL DP',
                'NOMINAL NON COD',
                'NOMINAL COD',
                'HARGA',
                'HPP',
                'ONGKIR',
                'FEE COD',
                'IKLAN',
                'KEUNTUNGAN',
                'CATATAN INTERNAL',
            ];

            $missingHeaders = array_diff(
                $requiredHeaders,
                $headers
            );

            if (!empty($missingHeaders)) {
                throw new \RuntimeException(
                    'Kolom Excel tidak ditemukan: ' .
                    implode(', ', $missingHeaders)
                );
            }

            $indexes = array_flip($headers);

            // ==========================================
            // 4. COUNTER HASIL IMPORT
            // ==========================================

            $ditambah = 0;
            $diperbarui = 0;
            $tidakBerubah = 0;
            $dilewati = 0;
            $gagal = 0;
            $errors = [];

            // ==========================================
            // 5. PROSES SETIAP BARIS
            // ==========================================

            foreach ($rows as $index => $row) {
                $nomorBaris = $index + 2;

                $get = function (string $kolom) use (
                    $row,
                    $indexes
                ) {
                    return $row[$indexes[$kolom]] ?? null;
                };

                $id = $this->teks($get('ID'));

                if (
                    $id === null ||
                    str_starts_with($id, '#')
                ) {
                    $dilewati++;
                    continue;
                }

                try {
                    $qty = $this->angka($get('QTY'));

                    if (
                        $qty !== null &&
                        ($qty < 0 || floor($qty) != $qty)
                    ) {
                        throw new \InvalidArgumentException(
                            'QTY harus bilangan bulat positif atau nol.'
                        );
                    }

                    // ==========================================
                    // 6. PEMETAAN KOLOM EXCEL KE MYSQL
                    // ==========================================

                    $data = [
                        'tgl_chat_masuk' => $this->tanggal(
                            $get('TGL CHAT MASUK')
                        ),

                        'tgl_order' => $this->tanggal(
                            $get('TGL ORDER')
                        ),

                        'cs' => $this->teks(
                            $get('CS')
                        ),

                        'nama_pembeli' => $this->teks(
                            $get('NAMA PEMBELI')
                        ),

                        'no_hp' => $this->teks(
                            $get('NO. HP')
                        ),

                        'alamat' => $this->teks(
                            $get('ALAMAT')
                        ),

                        'no_resi' => $this->teks(
                            $get('NO RESI')
                        ),

                        'status' => $this->teks(
                            $get('STATUS')
                        ),

                        'ekspedisi' => $this->teks(
                            $get('EKSPEDISI')
                        ),

                        'pembayaran' => $this->teks(
                            $get('PEMBAYARAN')
                        ),

                        'kategori' => $this->teks(
                            $get('KATEGORI')
                        ),

                        'qty' => $qty === null
                            ? null
                            : (int) $qty,

                        'notes' => $this->teks(
                            $get('NOTES')
                        ),

                        'nominal_dp' => $this->angka(
                            $get('NOMINAL DP')
                        ),

                        'nominal_non_cod' => $this->angka(
                            $get('NOMINAL NON COD')
                        ),

                        'nominal_cod' => $this->angka(
                            $get('NOMINAL COD')
                        ),

                        'harga' => $this->angka(
                            $get('HARGA')
                        ),

                        'hpp' => $this->angka(
                            $get('HPP')
                        ),

                        'ongkir' => $this->angka(
                            $get('ONGKIR')
                        ),

                        'fee_cod' => $this->angka(
                            $get('FEE COD')
                        ),

                        'iklan' => $this->angka(
                            $get('IKLAN')
                        ),

                        'keuntungan' => $this->angka(
                            $get('KEUNTUNGAN')
                        ),

                        'catatan_internal' => $this->teks(
                            $get('CATATAN INTERNAL')
                        ),
                    ];

                    // ==========================================
                    // 7. INSERT / UPDATE / SKIP
                    // ==========================================

                    $pesanan = Pesanan::firstOrNew([
                        'id_pesanan' => $id
                    ]);

                    $baru = !$pesanan->exists;

                    $pesanan->fill($data);

                    if ($baru) {
                        $pesanan->save();
                        $ditambah++;
                    } elseif ($pesanan->isDirty()) {
                        $pesanan->save();
                        $diperbarui++;
                    } else {
                        $tidakBerubah++;
                    }

                } catch (Throwable $e) {
                    $gagal++;

                    Log::warning('Baris import gagal', [
                        'baris' => $nomorBaris,
                        'id_pesanan' => $id,
                        'error' => $e->getMessage(),
                    ]);

                    // Batasi detail error dalam session
                    if (count($errors) < 10) {
                        $errors[] = [
                            'baris' => $nomorBaris,
                            'id_pesanan' => $id,
                            'pesan' => $e->getMessage(),
                        ];
                    }
                }
            }

            // ==========================================
            // 8. LANGSUNG KE DASHBOARD
            // ==========================================

            return redirect()
                ->route('dashboard')
                ->with('import_result', [
                    'success' => $gagal === 0,
                    'message' => $gagal === 0
                        ? 'Import Excel berhasil diselesaikan.'
                        : 'Import selesai dengan beberapa error.',
                    'ditambahkan' => $ditambah,
                    'diperbarui' => $diperbarui,
                    'tidak_berubah' => $tidakBerubah,
                    'baris_tanpa_id' => $dilewati,
                    'gagal' => $gagal,
                    'errors' => $errors,
                ]);

        } catch (Throwable $e) {

            // ==========================================
            // 9. ERROR IMPORT
            // ==========================================

            Log::error('Import Excel gagal', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->route('admin.import.index')
                ->with(
                    'error',
                    'Gagal import Excel: ' . $e->getMessage()
                );

        } finally {

            // ==========================================
            // 10. HAPUS FILE SEMENTARA
            // ==========================================

            if ($spreadsheet !== null) {
                $spreadsheet->disconnectWorksheets();
            }

            if ($path !== null && is_file($path)) {
                @unlink($path);
            }
        }
    }
}
