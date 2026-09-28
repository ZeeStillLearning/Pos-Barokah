<?php

namespace Database\Factories;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kategori;

/**
 * @extends Factory<Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_id' => Kategori::factory(),
            'sku' => 'SKU-' . $this->faker->unique()->numberBetween(100, 999),
            'nama' => ucwords($this->faker->words(3, true)),
            'harga' => $this->faker->numberBetween(20, 5_000) * 100,
            'stok' => $this->faker->numberBetween(5, 300),
            'aktif' => true,
        ];
    }
    public function habis(): static
    {
        return $this->state(fn() => ['stok' => 0]);
    }
    public function grosir(): static
    {
        return $this->state(fn() => [
            'harga' => $this->faker->numberBetween(20, 60) * 100,
            'stok' => $this->faker->numberBetween(300, 900),
        ]);
    }

    public function nonaktif(): static
    {
        return $this->state(fn() => ['aktif' => false]);
    }
}
