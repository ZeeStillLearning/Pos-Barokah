<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Kategori>
 */
class KategoriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = $this->faker->unique()->words(2, true);

        return [
            'kode' => Str::slug($nama, '_'),
            'nama' => ucwords($nama),
            'aktif' => true,
        ];
    }
    public function nonaktif(): static
    {
        return $this->state(fn() => ['aktif' => false]);
    }
}
