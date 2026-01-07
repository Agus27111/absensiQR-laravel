<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sekolah>
 */
class SekolahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_sekolah' => 'SMA Negeri 1',
            'npsn' => '20212345',
            'jam_masuk' => '07:00:00',
            'logo' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
