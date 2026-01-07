<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Display the billing page.
     */
    public function index()
    {
        $user = auth()->user();
        $sekolah = $user->sekolah;

        return view('pages/billing', [
            'title' => 'Billing',
            'titlepage' => 'Pembayaran Langganan',
            'sekolah' => $sekolah,
            'user' => $user,
        ]);
    }

    /**
     * Display package pricing page.
     */
    public function packages()
    {
        return view('pages/billing-packages', [
            'title' => 'Pilih Paket',
            'titlepage' => 'Pilih Paket Langganan',
        ]);
    }

    /**
     * Process payment (placeholder).
     */
    public function processPayment(Request $request)
    {
        // TODO: Implementasi payment gateway
        return redirect()->back()->with('success', 'Pembayaran berhasil diproses');
    }
}
