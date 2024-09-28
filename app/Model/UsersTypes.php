<?php

namespace App\Model;

/**
 * @property int $id
 * @property string $name
 */
class UsersTypes extends Model
{
    protected ?string $table = 'user_types';
    protected array $fillable = [
        'name',
    ];
}
