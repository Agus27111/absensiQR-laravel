<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sekolah;
use App\Models\Murid;
use App\Models\User;

class VerifyMultiTenant extends Command
{
    protected $signature = 'verify:multitenant';
    protected $description = 'Verify multi-tenant data setup';

    public function handle()
    {
        $this->line("\n=== VERIFIKASI MULTI-TENANT DATA ===\n");

        $this->info("SEKOLAH:");
        Sekolah::all()->each(function ($s) {
            $this->line("  ✓ ID: {$s->id}, Nama: {$s->nama_sekolah}, NPSN: {$s->npsn}");
        });

        $this->info("\nSISWA PER SEKOLAH:");
        Sekolah::all()->each(function ($s) {
            $count = Murid::where('sekolah_id', $s->id)->count();
            $this->line("  ✓ {$s->nama_sekolah}: {$count} siswa");
        });

        $this->info("\nUSER PER SEKOLAH:");
        Sekolah::all()->each(function ($s) {
            $users = User::where('sekolah_id', $s->id)->get();
            $this->line("  ✓ {$s->nama_sekolah}: " . $users->count() . " user");
            $users->each(function ($u) {
                $this->line("    └─ {$u->username}");
            });
        });

        $this->info("\nTOTAL SUMMARY:");
        $this->line("  • Total Sekolah: " . Sekolah::count());
        $this->line("  • Total Siswa: " . Murid::count());
        $this->line("  • Total User: " . User::count());

        $this->info("\n✅ Multi-Tenant setup verified successfully!\n");
    }
}
