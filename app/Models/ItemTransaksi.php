<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ItemTransaksi extends Model
{
    use HasFactory;

    protected $table = 'item_transaksi';
    protected $fillable = [
        'transaksi_id', 'produk_id', 'sku', 'nama_produk',
        'harga_satuan', 'kuantitas', 'diskon', 'total',
    ];

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'integer',
            'kuantitas' => 'integer',
            'diskon' => 'integer',
            'total' => 'integer',
        ];
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    /**
     * withTrashed(): baris struk tetap menunjuk produknya meski produk
     * itu sudah ditarik dari rak. Riwayat tidak boleh kehilangan rujukan.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id')->withTrashed();
    }
}