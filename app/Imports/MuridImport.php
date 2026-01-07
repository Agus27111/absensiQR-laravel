<?php

namespace App\Imports;

use App\Models\Murid;
use App\Models\Kelas;
use App\Models\Tahun;
use App\Models\Jenjang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;


class MuridImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validasi dasar: Kalau nama kosong, skip baris ini
        if (empty($row['nama'])) {
            return null;
        }

        $sekolahId = auth()->user()->sekolah_id;
        $nis = !empty($row['nis']) ? trim($row['nis']) : null;

        try {
            // Cari/Buat data pendukung
            $kelas = Kelas::firstOrCreate(['kelas' => trim($row['kelas']), 'sekolah_id' => $sekolahId]);
            $tahun = Tahun::firstOrCreate(['tahun' => trim($row['tahun']), 'sekolah_id' => $sekolahId]);
            $jenjang = Jenjang::firstOrCreate(['jenjang' => $row['jenjang'] ?? 'Umum', 'sekolah_id' => $sekolahId]);

            // LOGIKA PENEMPATAN:

            // 1. Jika ada NIS, gunakan updateOrCreate agar tidak double data
            if ($nis) {
                return Murid::updateOrCreate(
                    [
                        'nis' => $nis,
                        'sekolah_id' => $sekolahId
                    ],
                    [
                        'nama' => trim($row['nama']),
                        'kelas_id' => $kelas->id,
                        'tahun_id' => $tahun->id,
                        'jenjang_id' => $jenjang->id,
                    ]
                );
            }

            // 2. Jika NIS KOSONG, langsung buat baru (new Murid) 
            // agar tidak menimpa data murid lain yang NIS-nya juga null
            return new Murid([
                'nis' => null,
                'nama' => trim($row['nama']),
                'kelas_id' => $kelas->id,
                'tahun_id' => $tahun->id,
                'jenjang_id' => $jenjang->id,
                'sekolah_id' => $sekolahId,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal import: ' . $e->getMessage());
            return null;
        }
    }
}
