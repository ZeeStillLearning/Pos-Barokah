<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\RepositoriPengguna;
use App\Contracts\RepositoriProduk;
use App\Contracts\RepositoriTransaksi;
use App\Repositories\RepositoriPenggunaConfig;
use App\Repositories\RepositoriProdukArray;
use App\Repositories\RepositoriTransaksiBerkas;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RepositoriProduk::class, RepositoriProdukArray::class);

        // singleton: satu instance dipakai ulang selama satu request,
        // sehingga berkas JSON tidak dibuka berkali-kali.
        $this->app->singleton(RepositoriTransaksi::class, RepositoriTransaksiBerkas::class);

        $this->app->bind(RepositoriPengguna::class, RepositoriPenggunaConfig::class);
    }

    public function boot(): void
    {
        //
    }
}