<?php

namespace App\Http\Controllers;

use App\Models\IsAdmin;
use App\Models\Pengaturan;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function create(): never
    {
        abort(404);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request): never
    {
        abort(404);
    }

    /**
     * Display the resource.
     */
    public function show()
    {
        $user = auth()->user();

        // Pastikan sekolah diambil dengan relasi kelasnya (eager loading)
        $sekolah = Sekolah::with('kelas')->find($user->sekolah_id);

        if (!$sekolah) {
            return redirect()->back()->with('error', 'Data sekolah tidak ditemukan.');
        }

        $riwayat = \App\Models\Transaksi::where('sekolah_id', $sekolah->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('pages.pengaturan', [
            "title" => "Pengaturan",
            "data" => $sekolah,     // Digunakan untuk tab Umum
            "sekolah" => $sekolah,  // Digunakan untuk tab Kartu
            "riwayat" => $riwayat   // Digunakan untuk tab Billing
        ]);
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        $validasi = $request->validate([
            'nama_sekolah' => 'required',
            'jam_masuk' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Ambil sekolah milik user yang sedang login
        $sekolah = auth()->user()->sekolah;

        if (!$sekolah) {
            return back()->with('error', 'Sekolah tidak terdeteksi.');
        }

        if ($request->hasFile('logo')) {
            // Hapus logo lama
            if ($sekolah->logo && \Illuminate\Support\Facades\Storage::exists('public/' . $sekolah->logo)) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $sekolah->logo);
            }

            // Simpan ke folder public/logos
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validasi['logo'] = $logoPath;
        }

        $sekolah->update($validasi);

        return redirect('/pengaturan')->with('success', 'Pengaturan berhasil disimpan');
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(): never
    {
        abort(404);
    }
}
