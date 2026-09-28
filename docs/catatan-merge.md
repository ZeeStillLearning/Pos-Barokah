cat > docs/catatan-merge.md <<'EOF'
# Catatan Merge: Simulasi Konflik `DatabaseSeeder.php`

## Skenario
Dua cabang dari `main` yang sama (setelah PR #7) menambah satu baris komentar di titik yang sama, tepat di bawah `TransaksiContohSeeder::class,`.

| Cabang | Pembuat | Perubahan |
|---|---|---|
| `feature/konflik-a` | Zaki | `// Catatan dari cabang A` |
| `feature/konflik-b` | FauZee | `// Catatan dari cabang B` |

## Konflik
Perintah: `git merge origin/feature/konflik-a` (dari `feature/konflik-b`).

Hasil: `CONFLICT (content): Merge conflict in database/seeders/DatabaseSeeder.php`

Isi berkas **sebelum** diselesaikan:

```php
$this->call([
    KategoriProdukSeeder::class,
    TransaksiContohSeeder::class,
<<<<<<< HEAD
    // Catatan dari cabang B
=======
    // Catatan dari cabang A
>>>>>>> origin/feature/konflik-a
]);
```

## Penyelesaian
Kedua perubahan sah dan tidak saling meniadakan, jadi hasil yang benar memuat **keduanya**. Ketiga penanda dihapus, urutan seeder asli (`KategoriProdukSeeder` sebelum `TransaksiContohSeeder`) tidak diubah.

Isi berkas **sesudah** diselesaikan:

```php
$this->call([
    KategoriProdukSeeder::class,
    TransaksiContohSeeder::class,
    // Catatan dari cabang B
    // Catatan dari cabang A
]);
```

## Verifikasi
- `grep -rn "<<<<<<<\|>>>>>>>\|=======" app/ database/ routes/ config/` tidak menghasilkan apa pun.
- `php artisan migrate:fresh --seed` berjalan hijau.
- Commit merge: `merge: gabungkan cabang A ke B dan selesaikan konflik DatabaseSeeder`.

## Pelajaran
Konflik terjadi karena dua orang mengubah baris yang sama pada versi dasar yang sama. Menyelesaikan konflik berarti membaca maksud kedua perubahan lalu menyusun hasil yang memuat keduanya, bukan memilih salah satu blok.
EOF
