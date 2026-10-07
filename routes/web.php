<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

if (! app()->isProduction()) {
    Route::prefix('docs')->name('docs.')->group(function () {
        Route::get('/openapi.yaml', function () {
            return response()->file(base_path('docs/openapi.yaml'), [
                'Content-Type'  => 'application/yaml; charset=UTF-8',
                'Cache-Control' => 'no-store',
            ]);
        })->name('spesifikasi');

        Route::view('/swagger', 'dokumentasi.swagger')->name('swagger');
    });
}