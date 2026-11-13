<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IklanHarian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use RuntimeException;
use InvalidArgumentException;
use Throwable;

class ImportIklanController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        return view('admin.import-iklan');
    }

    private function tanggal($value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }
        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }
        $value = trim((string) $value);
        foreach (['!d/m/Y', '!d-m-Y', '!Y-m-d'] as $format) {
            try {
                $tanggal = Carbon::createFromFormat($format, $value);
                if ($tanggal && $tanggal->format(substr($format, 1)) === $value) {
                    return $tanggal->format('Y-m-d');
                }
            } catch (Throwable) {
                // Coba format berikutnya
            }
        }
        throw new InvalidArgumentException('Tanggal tidak valid: ' . $value);
    }

    private function bilangan($value): ?int
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }
        if (!is_numeric($value) || (float) $value < 0 || floor((float) $value) != (float) $value) {
            throw new InvalidArgumentException('Lead harus bilangan bulat >= 0: ' . (string) $value);
        }
        return (int) $value;
    }

    private function rupiah($value): ?float
    {
        if ($value === null || trim((string) $value) === '') {
            return null; // Kosong = belum dilaporkan, BUKAN 0
        }
        if (is_numeric($value)) {
            if ((float) $value < 0) {
                throw new InvalidArgumentException('Spend iklan tidak boleh negatif.');
            }
            return (float) $value;
        }

        $nilai = trim((string) $value);
        if (preg_match('/^(?:Rp\.?\s*)?[-–—]$/iu', $nilai)) {
            return 0;
        }
        $nilai = preg_replace('/^Rp\.?\s*/iu', '', $nilai);
        $nilai = preg_replace('/[\s\x{00A0}]/u', '', $nilai);

        // Rp8.667.381 / Rp8.667.381,50
        if (preg_match('/^\d{1,3}(?:\.\d{3})+(?:,\d+)?$/', $nilai)) {
            $nilai = str_replace(',', '.', str_replace('.', '', $nilai));
        } elseif (preg_match('/^\d+,\d+$/', $nilai)) {
            $nilai = str_replace(',', '.', $nilai);
        }
        if (!is_numeric($nilai) || (float) $nilai < 0) {
            throw new InvalidArgumentException('Spend iklan tidak valid: ' . (string) $value);
        }
        return (float) $nilai;
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls', 'extensions:xlsx,xls', 'max:10240'],
        ]);

        $path = null;
        $spreadsheet = null;

        try {
            $folder = storage_path('app/import-temp');
            File::ensureDirectoryExists($folder);
            $file = $request->file('file_excel');
            $nama = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
            $path = $file->move($folder, $nama)->getPathname();

            $reader = IOFactory::createReaderForFile($path);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);

            // Cari sheet berdasarkan baris header TANGGAL | NAMA | PRODUK | ...
            $sheet = null;
            $headers = null;
            $headerRow = null;
            foreach ($spreadsheet->getWorksheetIterator() as $candidate) {
                $lastHeaderRow = min($candidate->getHighestRow(), 15);
                for ($rowNo = 1; $rowNo <= $lastHeaderRow; $rowNo++) {
                    $lastColumn = $candidate->getHighestColumn();

                    $cells = $candidate->rangeToArray(
                        'A' . $rowNo . ':' . $lastColumn . $rowNo,
                        null,
                        false,
                        false,
                        false
                    )[0] ?? [];
                    $possible = array_map(
                        fn ($v) => strtoupper(preg_replace('/\s+/u', ' ', trim((string) ($v ?? '')))),
                        $cells
                    );
                    if (count(array_intersect(
                        ['TANGGAL', 'NAMA', 'PRODUK', 'LEAD META', 'LEAD CS', 'SPEND IKLAN'],
                        $possible
                    )) === 6) {
                        $sheet = $candidate;
                        $headers = $possible;
                        $headerRow = $rowNo;
                        break 2;
                    }
                }
            }
            if (!$sheet) {
                throw new RuntimeException(
                    'Header Excel tidak ditemukan. Wajib: TANGGAL, NAMA, PRODUK, LEAD META, LEAD CS, SPEND IKLAN.'
                );
            }

            $indexes = array_flip($headers);
            $rows = $sheet->toArray(null, false, false, false);
            $added = 0;
            $updated = 0;
            $unchanged = 0;
            $skipped = 0;
            $failed = 0;
            $errors = [];

            for ($i = $headerRow; $i < count($rows); $i++) {
                $row = $rows[$i];
                $rowNumber = $i + 1;
                $get = fn (string $col) => $row[$indexes[$col]] ?? null;

                // Abaikan baris total, baris kosong, dan jadwal tanpa tanggal.
                $rawDate = $get('TANGGAL');
                $cs = strtoupper(trim((string) ($get('NAMA') ?? '')));
                $produk = strtoupper(trim((string) ($get('PRODUK') ?? '')));
                if ($rawDate === null || trim((string) $rawDate) === '' || $cs === '' || $produk === '') {
                    $skipped++;
                    continue;
                }

                try {
                    $data = [
                        'lead_meta' => $this->bilangan($get('LEAD META')),
                        'lead_cs' => $this->bilangan($get('LEAD CS')),
                        'spend_iklan' => $this->rupiah($get('SPEND IKLAN')),
                    ];
                    $iklan = IklanHarian::firstOrNew([
                        'tanggal' => $this->tanggal($rawDate),
                        'cs' => $cs,
                        'produk' => $produk,
                    ]);
                    $isNew = !$iklan->exists;
                    $iklan->fill($data);
                    if ($isNew) {
                        $iklan->save();
                        $added++;
                    } elseif ($iklan->isDirty()) {
                        $iklan->save();
                        $updated++;
                    } else {
                        $unchanged++;
                    }
                } catch (Throwable $e) {
                    $failed++;
                    Log::warning('Baris import iklan gagal', [
                        'baris' => $rowNumber, 'cs' => $cs, 'produk' => $produk,
                        'error' => $e->getMessage(),
                    ]);
                    if (count($errors) < 10) {
                        $errors[] = 'Baris ' . $rowNumber . ': ' . $e->getMessage();
                    }
                }
            }

            return redirect()->route('admin.import-iklan.index')->with('import_iklan_result', [
                'ditambahkan' => $added,
                'diperbarui' => $updated,
                'tidak_berubah' => $unchanged,
                'dilewati' => $skipped,
                'gagal' => $failed,
                'errors' => $errors,
            ]);
        } catch (Throwable $e) {
            Log::error('Import Iklan Excel gagal', ['error' => $e->getMessage()]);
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        } finally {
            if ($spreadsheet !== null) {
                $spreadsheet->disconnectWorksheets();
            }
            if ($path !== null && is_file($path)) {
                @unlink($path);
            }
        }
    }
}
