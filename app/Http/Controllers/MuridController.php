<?php

namespace App\Http\Controllers;

use App\Imports\MuridImport;
use App\Models\Murid;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Tahun;
use App\Models\IsAdmin;
use App\Models\Sekolah;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MuridController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index_input()
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $kelas = Kelas::orderBy('kelas')->get();
        $tahun = Tahun::orderBy('tahun')->get();
        return view('pages/murid/input', [
            "title" => "Input Murid",
            "titlepage" => "Input Murid",
            "kelas" => $kelas,
            "tahun" => $tahun
        ]);
    }

    public function index_daftar()
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $user = auth()->user();
        // Filter murid berdasarkan sekolah - super admin bisa lihat semua, regular admin hanya sekolahnya
        $murid = Murid::with(['kelas', 'tahun'])
            ->when(!$user->super_admin, function ($query) use ($user) {
                return $query->where('sekolah_id', $user->sekolah_id);
            })
            ->get();
        return view('pages/murid/daftar', [
            "title" => "Daftar Murid",
            "titlepage" => "Daftar Murid",
            "murid" => $murid
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $kelas_id = Kelas::where('kelas', $request->kelas)->first()->id;
        $tahun_id = Tahun::where('tahun', $request->tahun)->first()->id;

        $validasi = $request->validate([
            'nis' => 'required|integer|unique:murids',
            'nama' => 'required|min:3|max:255',
            'kelas' => 'required'
        ]);

        $validasi['kelas_id'] = $kelas_id;
        $validasi['tahun_id'] = $tahun_id;
        $validasi['jenjang_id'] = 1; // Default, adjust based on your needs
        $validasi['sekolah_id'] = auth()->user()->sekolah_id;

        Murid::create($validasi);

        return redirect('/input-murid')->with('success', '');
    }

    /**
     * Display the specified resource.
     */
    public function show(Murid $murid) {}

    public function show_detail(Murid $murid)
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden
        $sekolah = Sekolah::find($murid->sekolah_id);


        $kelas = Kelas::get('kelas');
        $tahun = Tahun::get('tahun');
        return view('pages/murid/detail', [
            "title" => "Detail Murid",
            "titlepage" => "Detail Murid",
            "kelas_all" => Kelas::all(),
            "murid" => $murid,
            "data" => $murid,
            "tahun" => Tahun::all(),
            "absensi" => Absensi::latest()->where('murid_id', $murid->id)->limit(30)->get(),
            "qr" => QrCode::size(100)->generate($murid->nis),
            "sekolah" => $sekolah
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Murid $murid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, Murid $murid)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kelas_id' => 'required',
        ]);

        $data = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'kelas_id' => $request->kelas_id,
        ];

        if ($request->hasFile('photo')) {
            // Hapus foto lama dari storage jika ada
            if ($murid->photo) {
                Storage::delete('public/' . $murid->photo);
            }

            $path = $request->file('photo')->store('murid-photos', 'public');
            $data['photo'] = $path;
        }

        $murid->update($data);

        return back()->with('success', 'Data murid berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Verifikasi untuk User yang login apakah dia Admin
        $verifikasiAdmin = new IsAdmin();
        $verifikasiAdmin->isAdmin();
        // Jika status=1, maka akan lanjut kode di bawah
        // Jika status != 1, maka akan 403 Forbidden

        $getId = $request->murid;

        // Hapus data Murid sesuai dengan id-nya
        $validasi = $request->validate([
            'captcha' => 'required|captcha'
        ]);

        if ($validasi) {
            Murid::where('id', $getId)->delete();
            return redirect('/daftar-murid')->with('deleted', 'Data Murid berhasil di hapus!.');
        }

        return redirect('/detail-murid/' . $getId)->with('fail', '');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            // Store sekolah_id di session temporarily
            session(['import_sekolah_id' => auth()->user()->sekolah_id]);
            Excel::import(new MuridImport, $request->file('file'));
            session()->forget('import_sekolah_id');
            return back()->with('success', 'Data murid berhasil diimport!');
        } catch (\Exception $e) {
            Log::error('Error saat import: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function download_kartu_satuan($id)
    {
        $murid = Murid::with('kelas')->findOrFail($id);

        // AMBIL DATA SEKOLAH 
        $sekolah = Sekolah::find($murid->sekolah_id);



        $qr = base64_encode(
            QrCode::format('png')
                ->size(300) // besar dulu, nanti dikecilkan via CSS
                ->generate($murid->nis)
        );

        $pdf = PDF::loadView('pages/murid/kartu-s', [
            'data' => $murid,
            'sekolah' => $murid->sekolah,
            'qr' => $qr
        ]);

        return $pdf->download('Kartu-Absen-' . $murid->nis . '.pdf');
    }
}
