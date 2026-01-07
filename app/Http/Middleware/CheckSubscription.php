<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // 1. Biarkan Super Admin lewat tanpa cek langganan
        if ($user && $user->super_admin) {
            return $next($request);
        }

        $sekolah = $user?->sekolah;

        // 2. Cek apakah sekolah ada dan masa aktifnya masih berlaku
        // Gunakan optional() untuk menghindari error jika relasi sekolah kosong
        if (!$sekolah || !$sekolah->subscription_until || now()->gt($sekolah->subscription_until)) {

            // TODO: Implementasi halaman billing
            // Cek agar tidak redirect jika user sudah berada di halaman billing atau sedang logout
            // if (!$request->routeIs('billing.*') && !$request->routeIs('logout')) {
            //     return redirect()->route('billing.index')
            //         ->with('error', 'Akses dibatasi. Silahkan selesaikan pembayaran langganan sekolah Anda.');
            // }
        }

        return $next($request);
    }
}
