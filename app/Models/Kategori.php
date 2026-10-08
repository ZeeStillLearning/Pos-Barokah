<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Kategori as EnumKategori;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

final class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $fillable = ['kode', 'nama', 'aktif'];

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }

    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }

    public function itemTerjual(): HasManyThrough
    {
        return $this->hasManyThrough(
            ItemTransaksi::class,
            Produk::class,
            'kategori_id',
            'produk_id',
        )->withTrashedParents();
    }

    public function enum(): EnumKategori
    {
        return EnumKategori::from($this->kode);
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('kategori.aktif', true);
    }
}