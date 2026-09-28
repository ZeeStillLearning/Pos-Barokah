<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KategoriProdukSeeder::class,
            TransaksiContohSeeder::class,
            // Catatan dari cabang B
            // Catatan dari cabang A
        ]);
    }
}
