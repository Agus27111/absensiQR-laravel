<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Sekolah (Kakek)
        $sekolah = \App\Models\Sekolah::factory()->create();

        // 2. Buat Induk lainnya (Bapak) dan simpan ke variabel
        $jenjang = \App\Models\Jenjang::factory()->create(['sekolah_id' => $sekolah->id]);
        $tahun   = \App\Models\Tahun::factory()->create(['sekolah_id' => $sekolah->id]);
        $kelas   = \App\Models\Kelas::factory()->create(['sekolah_id' => $sekolah->id]);

        // 3. Buat User (Bapak)
        \App\Models\User::factory()->create(['sekolah_id' => $sekolah->id]);

        // 4. Buat Murid (Anak) dengan merujuk ke ID variabel di atas
        // Ini menjamin INTEGRITY CONSTRAINT tidak akan error
        \App\Models\Murid::factory(50)->create([
            'sekolah_id' => $sekolah->id,
            'jenjang_id' => $jenjang->id,
            'kelas_id'   => $kelas->id,
            'tahun_id'   => $tahun->id,
        ]);

        // 5. Baru panggil seeder eksternal
        $this->call([
            DemoAndSuperAdminSeeder::class,
            MultiTenantTestSeeder::class,
        ]);
    }
}
