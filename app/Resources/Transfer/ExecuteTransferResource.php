<?php
declare(strict_types=1);

namespace App\Resources\Transfer;

use App\Resources\Wallet\WalletResource;
use Hyperf\Resource\Json\JsonResource;

class ExecuteTransferResource extends JsonResource
{
    public function toArray(): array
    {
        return [
            'payerWallet' => WalletResource::make($this['payerWallet']),
            'payeeWallet' => WalletResource::make($this['payeeWallet'])
        ];
    }
}
