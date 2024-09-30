<?php

namespace App\Repositories\UserTypes;

use App\Model\UserTypes;
use App\Traits\InstantiateTrait;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;

class UserTypesEloquentRepository extends UserTypes implements UserTypesRepositoryInterface
{
    use InstantiateTrait;

    public function createUserType(string $name): UserTypes|Model
    {
        return UserTypes::create(['name' => $name]);
    }

    public function findUserTypeOrFail(int|string $id): UserTypes|Collection|Model|array
    {
        return UserTypes::findOrFail($id);
    }
}
