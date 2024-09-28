<?php

namespace App\Resources\Wallet;

use App\Model\Wallets;
use Hyperf\Resource\Json\JsonResource;

/**
 * @mixin Wallets
 */
class WalletResource extends JsonResource
{
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'balance' => $this->balance
        ];
    }
}
