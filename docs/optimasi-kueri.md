# Laporan Optimasi Kueri — Modul 5

Volume data saat pengukuran: 283 struk, 567 baris struk, 30 produk, 3 pemasok.
Alat ukur: header X-Jumlah-Kueri (middleware HitungKueri) dan Telescope.

## 1. Jumlah Kueri Sebelum dan Sesudah
| Endpoint | Sebelum | Sesudah | Perbaikan |
|---|:---:|:---:|---|
| `GET /transaksi?tanggal=<kemarin>` | 41 | 2 | `with('item')` pada relasi hasMany |
| `GET /transaksi?tanggal=<hari ini>` | 4 | 2 | Eager loading relasi item |
| `GET /transaksi/{nomor}` | 2 | 2 | Tetap; lewat relasi |
| `GET /produk` | 1 | 2 | Join manual diubah ke `with('kategori:id,kode')` |
| `GET /laporan/kategori` | — | 1 | `withSum()` pada hasManyThrough |

*Catatan: angka 41 tumbuh mengikuti jumlah struk (1 + N). Angka 2 konstan tidak tumbuh.*

## 2. Pemanfaatan Indeks
| Tabel | Indeks | Status Awal | Tindakan Modul 5 |
|---|---|---|---|
| `transaksi` | `(status, created_at)` | Ada, tidak terpakai (`DATE()`) | Scope `tanggal()` diubah menjadi rentang waktu |
| `transaksi` | `(created_at)` | Belum ada | Tambah migrasi indeks tunggal |
| `pemasok_produk` | `(produk_id, pemasok_id)` | — | Unique constraint merangkap covering index |

## 3. Bukti EXPLAIN
- **Laporan Harian:** `type` berubah dari `ALL` (rows: 283) menjadi `range` (rows: 2), menggunakan `transaksi_status_created_at_index`.
- **Daftar Struk per Tanggal:** `type` berubah dari `ALL` menjadi `range` (rows: 40), menggunakan `transaksi_created_at_index`.

## 4. Temuan Domain yang Diselesaikan
1. `total_bayar_format` dibersihkan dengan `Arr::except()` saat persistensi transaksi.
2. Metadata pembatalan (`alasan_batal`, `dibatalkan_oleh`, `dibatalkan_pada`) disertakan pada payload response struk.
3. SKU tidak terdaftar menghasilkan response status 404 (`produk_tidak_ditemukan`).
4. Sanitasi parameter `batas` pada laporan terlaris di-clamp antara 1 hingga 20.

## 5. Kepatuhan Kontrak
- `npx @redocly/cli lint docs/openapi.yaml` -> 0 error.
- `newman` via `prism proxy` -> 0 violations, 100% assertions green.