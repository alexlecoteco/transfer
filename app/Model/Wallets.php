<?php

namespace App\Model;
/**
 * @property int $id
 * @property int $user_id
 * @property int $balance
 */
class Wallets extends Model
{
    protected ?string $table = 'wallets';

    protected array $fillable = [
        'user_id',
        'balance',
    ];
}
