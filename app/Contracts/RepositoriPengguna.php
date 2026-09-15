<?php

declare(strict_types=1);

namespace App\Contracts;

interface RepositoriPengguna
{
    /** @return array<int, array<string, string>> */
    public function semua(): array;
}