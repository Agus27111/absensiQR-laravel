<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\Kelas;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\IsAdmin;

class PdfController extends Controller
{

    public function downloadKartuSatuan(Murid $murid)
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $qr = QrCode::size(100)->generate($murid->nis);

        $pdf = PDF::loadView('pages.murid.kartu-s', [
            'data' => $murid,
            'sekolah' => $murid->sekolah,
            'qr' => $qr
        ]);
        return $pdf->download('Kartu-Absen-' . $murid->nis . '.pdf');
    }




    public function downloadKartuMassal(Murid $murid, Kelas $kelas)
    {
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();

        $muridList = Murid::where('kelas_id', $kelas->id)->get();

        if ($muridList->count() > 0) {
            // Ambil data sekolah dari salah satu murid (misal murid pertama)
            $sekolah = $muridList->first()->sekolah;

            $data = [];

            foreach ($muridList as $m) {
                $data[] = [
                    'nama' => $m->nama,
                    'kelas' => $m->kelas->kelas,
                    'nis' => $m->nis,
                    'qr' => QrCode::size(100)->generate($m->nis),
                    'photo' => $m->photo
                ];
            }

            $pdf = PDF::loadView('/pages/murid/kartu-m', [
                'data' => $data,
                'sekolah' => $sekolah // WAJIB DIKIRIM agar sidebar dan header tidak error
            ]);

            return $pdf->download('Kartu-Absen-' . $kelas->kelas . '.pdf');
        } else {
            return redirect('/kelas/daftar')->with('fail_qr', '');
        }
    }
}
