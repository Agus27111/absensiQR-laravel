<?php
// Quick verification script
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Sekolah;
use App\Models\Murid;
use App\Models\User;

echo "\n=== VERIFIKASI MULTI-TENANT DATA ===\n\n";

echo "SEKOLAH:\n";
Sekolah::all()->each(function ($s) {
    echo "- ID: {$s->id}, Nama: {$s->nama_sekolah}, NPSN: {$s->npsn}\n";
});

echo "\nSISWA PER SEKOLAH:\n";
Sekolah::all()->each(function ($s) {
    $count = Murid::where('sekolah_id', $s->id)->count();
    echo "- Sekolah ID {$s->id} ({$s->nama_sekolah}): {$count} siswa\n";
});

echo "\nUSER PER SEKOLAH:\n";
Sekolah::all()->each(function ($s) {
    $users = User::where('sekolah_id', $s->id)->get();
    echo "- Sekolah ID {$s->id} ({$s->nama_sekolah}): " . $users->count() . " user\n";
    $users->each(function ($u) {
        echo "  └─ Username: {$u->username}\n";
    });
});

echo "\nSUPERADMIN & DEMO USERS:\n";
User::whereIn('username', ['superadmin', 'demo'])->get()->each(function ($u) {
    echo "- Username: {$u->username}, Sekolah ID: {$u->sekolah_id}\n";
});

echo "\n";
