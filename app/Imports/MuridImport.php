<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Murid;
use App\Models\Kelas;
use App\Models\Tahun;
use Illuminate\Support\Facades\Log;

class MuridImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info('Import murid:', $row);

        try {
            $kelas = Kelas::firstOrCreate(['kelas' => trim($row['kelas'])]);
            $tahun = Tahun::firstOrCreate(['tahun' => trim($row['tahun'])]);

            return new Murid([
                'nis' => trim($row['nis']),
                'nama' => trim($row['nama']),
                'kelas_id' => $kelas->id,
                'tahun_id' => $tahun->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error import murid: ' . $e->getMessage(), ['row' => $row]);
            return null; // supaya import lanjut meskipun ada error di baris ini
        }
    }
}
