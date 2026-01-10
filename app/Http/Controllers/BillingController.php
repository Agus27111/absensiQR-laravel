<?php

namespace App\Imports; // Pastikan namespace benar, biasanya App\Http\Controllers
namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

class BillingController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $sekolah = auth()->user()->sekolah;
        $riwayat = Transaksi::where('sekolah_id', $sekolah->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.billing', [
            "title" => "Billing",         // Tambahkan ini untuk tag <title>
            "titlepage" => "Pembayaran",  // Tambahkan ini jika layout butuh titlepage
            "sekolah" => $sekolah,
            "riwayat" => $riwayat
        ]);
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();
        $sekolah = $user->sekolah;

        // Tentukan harga (misal paket 1 bulan = 100.000)
        $harga = 100000;
        $orderId = 'INV-' . Str::upper(Str::random(10));

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $harga,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        try {
            // Ambil Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Simpan data transaksi ke database kita dengan status pending
            Transaksi::create([
                'sekolah_id' => $sekolah->id,
                'order_id' => $orderId,
                'gross_amount' => $harga,
                'status' => 'pending',
                'snap_token' => $snapToken
            ]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $transaksi = Transaksi::where('order_id', $request->order_id)->first();

                if ($transaksi && $transaksi->status != 'success') {
                    $transaksi->update(['status' => 'success']);

                    // Update masa aktif sekolah (Tambah 30 hari)
                    $sekolah = $transaksi->sekolah;
                    $currentUntil = $sekolah->subscription_until ? \Carbon\Carbon::parse($sekolah->subscription_until) : now();

                    // Jika sudah expired, mulai dari hari ini. Jika belum, tambah dari tanggal expired.
                    $newUntil = ($currentUntil->isPast()) ? now()->addDays(30) : $currentUntil->addDays(30);

                    $sekolah->update([
                        'subscription_until' => $newUntil,
                        'status' => 'active'
                    ]);
                }
            }
        }
    }
}
