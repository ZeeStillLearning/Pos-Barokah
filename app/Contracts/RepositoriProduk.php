<?php

declare(strict_types=1);

namespace App\Contracts;

interface RepositoriProduk
{
    /** @return array<int, array<string, mixed>> */
    public function semua(): array;

    /** @return array<string, mixed>|null */
    public function cariSku(string $sku): ?array;

    /**
     * Produk beserta daftar pemasok dan harga belinya (AB-13).
     * @return array<string, mixed>|null
     */
    public function cariPemasok(string $sku): ?array;

    /** Mengunci baris produk sampai transaksi selesai, lalu mengembalikan stok terkini. */
    public function kunciStok(string $sku): int;

    /** $selisih negatif mengurangi stok, positif mengembalikannya. */
    public function ubahStok(string $sku, int $selisih): void;
}