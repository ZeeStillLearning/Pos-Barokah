<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoriProduk;
use App\Models\Pemasok;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Builder;

final class RepositoriProdukEloquent implements RepositoriProduk
{
    public function semua(): array
    {
        return $this->dasar()
            ->orderBy('produk.nama')
            ->get()
            ->map($this->keArray(...))
            ->all();
    }

    public function cariSku(string $sku): ?array
    {
        $produk = $this->dasar(aktifSaja: false)
            ->where('produk.sku', $this->normal($sku))
            ->first();

        return $produk === null ? null : $this->keArray($produk);
    }

    public function cariPemasok(string $sku): ?array
    {
        $produk = Produk::query()
            ->with('pemasok')
            ->where('sku', $this->normal($sku))
            ->first();

        if ($produk === null) {
            return null;
        }

        return [
            'sku'     => $produk->sku,
            'nama'    => $produk->nama,
            'harga'   => $produk->harga,
            'pemasok' => $produk->pemasok
                ->map(static fn (Pemasok $p): array => [
                    'kode'       => $p->kode,
                    'nama'       => $p->nama,
                    'kota'       => $p->kota,
                    'harga_beli' => (int) $p->pasokan->harga_beli,
                    'utama'      => (bool) $p->pasokan->utama,
                ])
                ->all(),
        ];
    }

    public function kunciStok(string $sku): int
    {
        return (int) Produk::query()
            ->where('sku', $this->normal($sku))
            ->lockForUpdate()
            ->value('stok');
    }

    public function ubahStok(string $sku, int $selisih): void
    {
        Produk::query()
            ->where('sku', $this->normal($sku))
            ->increment('stok', $selisih);
    }

    private function dasar(bool $aktifSaja = true): Builder
    {
        return Produk::query()
            ->with('kategori:id,kode') // id wajib disertakan
            ->when($aktifSaja, fn (Builder $q) => $q->aktif());
    }

    private function normal(string $sku): string
    {
        return strtoupper(trim($sku));
    }

    /** @return array<string, mixed> */
    private function keArray(Produk $produk): array
    {
        return [
            'sku'      => $produk->sku,
            'nama'     => $produk->nama,
            'kategori' => $produk->kategori->kode,
            'harga'    => $produk->harga,
            'stok'     => $produk->stok,
        ];
    }
}