<?php

namespace App\Repositories\Users;

use App\Model\Users;
use App\Traits\InstantiateTrait;
use Hyperf\Database\Model\Model;

class UsersEloquentRepository extends Users implements UsersInterface
{
    use InstantiateTrait;

    public function createUser(
        string $name,
        string $email,
        int $document,
        string $password,
        ?int $userType = null
    ): Users|Model
    {
        return Users::create(
            [
                'name' => $name,
                'email' => $email,
                'document' => $document,
                'password' => $password,
                'user_type' => $userType
            ]
        );
    }

    public function findUserOrFail(int|string $id): Users|Model
    {
        return Users::findOrFail($id);
    }
}
