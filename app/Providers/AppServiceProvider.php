<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\RepositoriPengguna;
use App\Contracts\RepositoriProduk;
use App\Contracts\RepositoriTransaksi;
use App\Repositories\RepositoriPenggunaConfig;
use App\Repositories\RepositoriProdukEloquent;
use App\Repositories\RepositoriTransaksiEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RepositoriProduk::class, RepositoriProdukEloquent::class);
        $this->app->bind(RepositoriTransaksi::class, RepositoriTransaksiEloquent::class);
        $this->app->bind(RepositoriPengguna::class, RepositoriPenggunaConfig::class);
    }

    public function boot(): void
    {
        /*
        | Mode ketat Eloquent, SELAIN di produksi. Satu baris ini
        | menyalakan tiga detektor sekaligus:
        |
        |   preventLazyLoading()                   relasi dimuat diam-diam (N+1)
        |   preventSilentlyDiscardingAttributes()  kolom dibuang diam-diam saat mass assignment
        |   preventAccessingMissingAttributes()    membaca kolom yang tidak ikut di-SELECT
        */
        Model::shouldBeStrict(! $this->app->isProduction());
    }
}