<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sekolah;
use App\Models\Tahun;
use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Murid;
use Illuminate\Support\Facades\Hash;

class MultiTenantTestSeeder extends Seeder
{
    /**
     * Seed the application's database with multiple schools for testing multi-tenant.
     */
    public function run(): void
    {
        // ==================== SEKOLAH 1: SMA NEGERI 1 ====================
        $sekolah1 = Sekolah::firstWhere('npsn', '20212345');
        if (!$sekolah1) {
            $sekolah1 = Sekolah::create([
                'nama_sekolah' => 'SMA Negeri 1',
                'npsn' => '20212345',
                'jam_masuk' => '07:00:00',
                'status_langganan' => 'active',
                'subscription_until' => now()->addMonths(12),
            ]);
        }

        // PERBAIKAN: Tambahkan sekolah_id
        $tahun1 = Tahun::where('tahun', '2025/2026')->where('sekolah_id', $sekolah1->id)->first();
        if (!$tahun1) {
            $tahun1 = Tahun::create([
                'tahun' => '2025/2026',
                'sekolah_id' => $sekolah1->id,
            ]);
        }

        // PERBAIKAN: Pastikan kolom 'nama_jenjang' sesuai dengan migration kamu
        $jenjang1 = Jenjang::where('sekolah_id', $sekolah1->id)->first();
        if (!$jenjang1) {
            $jenjang1 = Jenjang::create([
                'name' => 'Sekolah Menengah Atas',
                'sekolah_id' => $sekolah1->id,
            ]);
        }

        // PERBAIKAN: Tambahkan sekolah_id
        $kelas1 = Kelas::where('kelas', 'X-IPA')->where('sekolah_id', $sekolah1->id)->first();
        if (!$kelas1) {
            $kelas1 = Kelas::create([
                'kelas' => 'X-IPA',
                'sekolah_id' => $sekolah1->id,
            ]);
        }

        // User untuk Sekolah 1
        $user1 = User::firstWhere('username', 'admin_sma1');
        if (!$user1) {
            $user1 = User::create([
                'username' => 'admin_sma1',
                'password' => Hash::make('admin123'),
                'super_admin' => false,
                'sekolah_id' => $sekolah1->id,
            ]);
        }

        // Murid untuk Sekolah 1
        $muridCount1 = Murid::where('sekolah_id', $sekolah1->id)->count();
        if ($muridCount1 == 0) {
            for ($i = 1; $i <= 25; $i++) {
                Murid::create([
                    'nis' => 11020201000 + $i,
                    'nama' => 'Siswa SMA 1 Nomor ' . $i,
                    'jenjang_id' => $jenjang1->id,
                    'kelas_id' => $kelas1->id,
                    'tahun_id' => $tahun1->id,
                    'sekolah_id' => $sekolah1->id,
                ]);
            }
        }

        // ==================== SEKOLAH 2: SMKN 2 BANDUNG ====================
        $sekolah2 = Sekolah::create([
            'nama_sekolah' => 'SMKN 2 Bandung',
            'npsn' => '20219876',
            'jam_masuk' => '06:30:00',
            'status_langganan' => 'active',
            'subscription_until' => now()->addMonths(6),
        ]);

        // PERBAIKAN: Tambahkan sekolah_id
        $tahun2 = Tahun::create([
            'tahun' => '2024/2025',
            'sekolah_id' => $sekolah2->id,
        ]);

        // PERBAIKAN: Pastikan kolom sesuai
        $jenjang2 = Jenjang::create([
            'name' => 'Sekolah Menengah Kejuruan',
            'sekolah_id' => $sekolah2->id,
        ]);

        // PERBAIKAN: Tambahkan sekolah_id
        $kelas2 = Kelas::create([
            'kelas' => 'XI-RPL',
            'sekolah_id' => $sekolah2->id,
        ]);

        // User untuk Sekolah 2
        $user2 = User::create([
            'username' => 'admin_smkn2',
            'password' => Hash::make('admin456'),
            'super_admin' => false,
            'sekolah_id' => $sekolah2->id,
        ]);

        // Murid untuk Sekolah 2
        for ($i = 1; $i <= 30; $i++) {
            Murid::create([
                'nis' => 12020202000 + $i,
                'nama' => 'Siswa SMKN 2 Nomor ' . $i,
                'jenjang_id' => $jenjang2->id,
                'kelas_id' => $kelas2->id,
                'tahun_id' => $tahun2->id,
                'sekolah_id' => $sekolah2->id,
            ]);
        }

        $this->command->info('Multi-Tenant Test Data Created Successfully!');
    }
}
