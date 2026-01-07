<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use App\Models\Absensi;
use App\Models\ManajemenWaktu;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Super Admin dashboard - tampilkan monitoring semua sekolah
        if ($user->super_admin) {
            return $this->superAdminDashboard();
        }

        // Regular Admin dashboard - tampilkan data sekolah mereka
        return $this->tenantAdminDashboard();
    }

    /**
     * Dashboard untuk Super Admin - Monitoring semua sekolah
     */
    private function superAdminDashboard()
    {
        $totalSekolah = Sekolah::count();
        $sekolahAktif = Sekolah::where('status_langganan', 'active')->count();
        $sekolahKadaluarsa = Sekolah::where('status_langganan', 'expired')->count();
        $sekolahTertunda = Sekolah::where('status_langganan', 'pending')->count();

        $totalMurid = Murid::count();
        $totalUser = \App\Models\User::where('super_admin', false)->count();

        $sekolahList = Sekolah::with(['users' => function ($query) {
            $query->where('super_admin', false);
        }])
            ->withCount('murids')
            ->orderBy('nama_sekolah')
            ->get();

        return view('pages/beranda-superadmin', [
            "title" => "Dashboard Admin",
            "titlepage" => "Dashboard Monitoring",
            "totalSekolah" => $totalSekolah,
            "sekolahAktif" => $sekolahAktif,
            "sekolahKadaluarsa" => $sekolahKadaluarsa,
            "sekolahTertunda" => $sekolahTertunda,
            "totalMurid" => $totalMurid,
            "totalUser" => $totalUser,
            "sekolahList" => $sekolahList,
        ]);
    }

    /**
     * Dashboard untuk Tenant Admin - Data sekolah mereka saja
     */
    private function tenantAdminDashboard()
    {
        $manajemenWaktu = new ManajemenWaktu();
        $tanggalHariIni = $manajemenWaktu->ambilTanggal();
        $hariIni = $manajemenWaktu->ambilHari();
        $bulanHariIni = $manajemenWaktu->ambilBulan();
        $tahunHariIni = $manajemenWaktu->ambilTahun();
        $waktuDatabase = $manajemenWaktu->ambilTahunBulanTanggal();

        $user = auth()->user();
        $sekolahId = $user->sekolah_id;

        // Filter data berdasarkan sekolah
        $totalMurid = Murid::where('sekolah_id', $sekolahId)->count();

        $dataAbsenMasuk = Absensi::with(['murid', 'kelas'])
            ->whereHas('murid', function ($query) use ($sekolahId) {
                $query->where('sekolah_id', $sekolahId);
            })
            ->where('status', 1)
            ->whereDate('created_at', $waktuDatabase)
            ->get();

        $dataAbsenAlpa = Absensi::with(['murid', 'kelas'])
            ->whereHas('murid', function ($query) use ($sekolahId) {
                $query->where('sekolah_id', $sekolahId);
            })
            ->whereIn('status', [0, 3])
            ->whereDate('created_at', $waktuDatabase)
            ->get();

        $dataAbsenTerlambat = Absensi::with(['murid', 'kelas'])
            ->whereHas('murid', function ($query) use ($sekolahId) {
                $query->where('sekolah_id', $sekolahId);
            })
            ->where('status', 2)
            ->whereDate('created_at', $waktuDatabase)
            ->get();

        $absenAlpa = count($dataAbsenAlpa);
        $absenMasuk = count($dataAbsenMasuk);
        $absenTerlambat = count($dataAbsenTerlambat);

        $muridAll = Murid::with('kelas')
            ->where('sekolah_id', $sekolahId)
            ->orderBy('nama')
            ->get();

        return view('pages/beranda', [
            "title" => "Beranda",
            "titlepage" => "Beranda",
            "absenMasuk" => $absenMasuk,
            "absenTerlambat" => $absenTerlambat,
            "absenAlpa" => $absenAlpa,
            "muridMasuk" => $dataAbsenMasuk,
            "muridTerlambat" => $dataAbsenTerlambat,
            "muridAlpa" => $dataAbsenAlpa,
            "totalMurid" => $totalMurid,
            "hari" => $hariIni,
            "tanggal" => $tanggalHariIni,
            "bulan" => $bulanHariIni,
            "tahun" => $tahunHariIni,
            "muridAll" => $muridAll
        ]);
    }
}
