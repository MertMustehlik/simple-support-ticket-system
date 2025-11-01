<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthInterface
{
    /**
     * @return array{user: User, token: string}
     */
    public function register(array $data): array;

    /**
     * @return array{user: User, token: string}
     */
    public function login(array $data): array;
}
