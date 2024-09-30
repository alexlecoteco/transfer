<?php

namespace App\Repositories\Users;

interface UsersInterface
{
    public function findUserOrFail(int|string $id);

    public function createUser(
        string $name,
        string $email,
        int $document,
        string $password,
        ?int $userType = null
    );
}
