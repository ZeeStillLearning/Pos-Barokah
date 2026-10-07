<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\MetodeBayar;
use App\Domain\StatusTransaksi;
use App\Models\ItemTransaksi;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'nomor', 'kasir', 'member', 'metode_bayar', 'status',
        'subtotal', 'diskon_grosir', 'diskon_member', 'total_diskon',
        'dpp', 'ppn', 'total', 'pembulatan', 'total_bayar',
        'dibayar', 'kembalian',
        'alasan_batal', 'dibatalkan_oleh', 'dibatalkan_pada',
    ];

    protected function casts(): array
    {
        return [
            'member'          => 'boolean',
            'metode_bayar'    => MetodeBayar::class,
            'status'          => StatusTransaksi::class,
            'dibatalkan_pada' => 'datetime',
        ];
    }

    /* ---------------- Relasi ---------------- */

    public function item(): HasMany
    {
        return $this->hasMany(ItemTransaksi::class, 'transaksi_id');
    }

    /* ---------------- Query Scope ---------------- */

    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('transaksi.status', StatusTransaksi::Selesai);
    }

    public function scopeTanggal(Builder $query, string $tanggal): Builder
    {
        $awal = CarbonImmutable::parse($tanggal)->startOfDay();

        return $query
            ->where('transaksi.created_at', '>=', $awal)
            ->where('transaksi.created_at', '<', $awal->addDay());
    }
}