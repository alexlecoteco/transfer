<?php

namespace App\Model;

/**
 * @property int $id
 * @property string $name
 */
class UserTypes extends Model
{
    protected ?string $table = 'user_types';
    protected array $fillable = [
        'name',
    ];
}
