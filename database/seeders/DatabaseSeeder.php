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
        // Create parent data first for default seeding
        $sekolah = \App\Models\Sekolah::factory()->create();

        \App\Models\Murid::factory(50)->create([
            'sekolah_id' => $sekolah->id
        ]);
        \App\Models\Tahun::factory(1)->create();
        \App\Models\Jenjang::factory(1)->create();
        \App\Models\Kelas::factory(1)->create();

        // Then create related data
        \App\Models\User::factory(1)->create();
        \App\Models\Murid::factory(50)->create();
        //\App\Models\Absensi::factory(1000)->create();

        $this->call([
            DemoAndSuperAdminSeeder::class,
            MultiTenantTestSeeder::class,
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // \App\Models\Murid::create([
        //     'nis' => '332',
        //     'nama' => 'Helmi',
        //     'kelas' => 'XI',
        //     'tahun' => '2020'
        // ]);

        // \App\Models\Murid::create([
        //     'nis' => '335',
        //     'nama' => 'Johan',
        //     'kelas' => 'XII',
        //     'tahun' => '2019'
        // ]);

        // \App\Models\Absensi::create([
        //     'murid_id' => '1',
        //     'hari' => 'Senin',
        //     'tanggal' => '2023-05-01',
        //     'jam_absen' => '2023-05-12 01:00:00',
        //     'hadir' => '1'
        // ]);

        // \App\Models\Absensi::create([
        //     'murid_id' => '2',
        //     'hari' => 'Selasa',
        //     'tanggal' => '2023-05-01',
        //     'jam_absen' => '2023-05-12 01:00:00',
        //     'hadir' => '1'
        // ]);
    }
}
