<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\RepositoriPengguna;

final class RepositoriPenggunaConfig implements RepositoriPengguna
{
    public function semua(): array
    {
        return array_values(array_map(
            static fn (array $identitas): array => [
                'nama' => $identitas['nama'],
                'peran' => $identitas['peran'],
            ],
            (array) config('pos.kasir'),
        ));
    }
}
