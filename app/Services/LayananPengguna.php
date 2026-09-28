<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\RepositoriPengguna;

final class LayananPengguna
{
    public function __construct(
        private readonly RepositoriPengguna $repositori,
    ) {}

    /** @return array<int, array<string, string>> */
    public function daftar(): array
    {
        return $this->repositori->semua();
    }
}
