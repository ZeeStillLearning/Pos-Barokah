# Alur Request — POST /api/v1/pos/transaksi

Aplikasi kasir
  |
  | POST /api/v1/pos/transaksi
  | X-API-Key: kasir-dev-001
  | { "item": [...], "metode_bayar": "tunai", "dibayar": 150000 }
  v
1. public/index.php ................ single entry point
2. bootstrap/app.php ............... build aplikasi, middleware didaftarkan
3. Service Provider ................ RepositoriProduk & RepositoriTransaksi di-bind
4. Middleware global (grup api) .... CatatRequest -> mulai stopwatch, buat X-Request-Id
5. Router .......................... cocokkan POST + URI -> TransaksiController@store
6. Middleware rute (berurutan):
   a. kasir ..................... validasi X-API-Key, sisipkan identitas kasir
   b. jam.buka .................. tolak bila di luar jam operasional -> 403
7. Controller ...................... validasi bentuk masukan, panggil LayananKasir
8. Service (LayananKasir) .......... AB-1 s.d. AB-11 dihitung di sini
9. Repository (RepositoriTransaksiBerkas) .. struk disimpan ke transaksi.json
10. Response 201 ................... dibentuk controller
   |
   v arah balik: middleware dilewati dengan URUTAN TERBALIK
11. jam.buka (tidak melakukan apa-apa pada arah balik)
12. kasir (tidak melakukan apa-apa pada arah balik)
13. CatatRequest ................... hitung durasi, tulis log (jika >100ms atau 4xx/5xx),
                                      tempel header X-Request-Id & X-Response-Time
   |
   v
Aplikasi kasir menerima 201 Created