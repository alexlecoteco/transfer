<?php

namespace App\Repositories\UserTypes;

interface UserTypesRepositoryInterface
{
    public function findUserTypeOrFail(int|string $id);
}
