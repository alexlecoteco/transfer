<?php

namespace App\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $document
 * @property string $password
 * @property string $email
 * @property int $user_type
 */
class Users extends Model
{
    protected ?string $table = 'users';
    protected array $fillable = [
        'name',
        'document',
        'password',
        'email'
    ];
}
