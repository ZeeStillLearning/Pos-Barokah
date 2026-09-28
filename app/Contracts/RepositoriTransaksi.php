<?php

declare(strict_types=1);

namespace App\Contracts;

interface RepositoriTransaksi
{
    /** @return array<int, array<string, mixed>> */
    public function tanggal(string $tanggal): array;

    /** @return array<string, mixed>|null */
    public function cariNomor(string $nomor): ?array;

    /** @param array<string, mixed> $transaksi */
    public function simpan(array $transaksi): void;

    /** @param array<string, mixed> $perubahan */
    public function perbarui(string $nomor, array $perubahan): void;

    public function urutanBerikutnya(string $tanggal): int;
}
