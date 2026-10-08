<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Uang;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produk';

    // "stok" sengaja tidak masuk $fillable: hanya berubah lewat LayananKasir.
    protected $fillable = ['kategori_id', 'sku', 'nama', 'harga', 'aktif'];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'stok'  => 'integer',
            'aktif' => 'boolean',
        ];
    }

    /* ---------------- Relasi ---------------- */

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function pemasok(): BelongsToMany
    {
        return $this->belongsToMany(Pemasok::class, 'pemasok_produk')
            ->as('pasokan')
            ->withPivot(['harga_beli', 'utama'])
            ->withTimestamps()
            ->orderByPivot('utama', 'desc')
            ->orderByPivot('harga_beli');
    }

    /* ---------------- Query Scope ---------------- */

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('produk.aktif', true);
    }

    public function scopeTersedia(Builder $query): Builder
    {
        return $query->where('produk.stok', '>', 0);
    }

    public function scopeKategoriKode(Builder $query, string $kode): Builder
    {
        return $query->whereRelation('kategori', 'kode', $kode);
    }

    public function hargaFormat(): string
    {
        return (new Uang($this->harga))->format();
    }
}