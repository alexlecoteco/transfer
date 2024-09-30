<?php

namespace App\Repositories\Wallets;

use App\Model\Wallets;
use App\Traits\InstantiateTrait;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;

class WalletsEloquentRepository extends Wallets implements WalletRepositoryInterface
{
    use InstantiateTrait;

    public function createWallet(int $user_id, int $balance): Wallets|Model
    {
        return Wallets::create(
            [
                'user_id' => $user_id,
                'balance' => $balance
            ]
        );
    }
    public function findWalletByUserId(int|string $user_id): Wallets|Model|Builder
    {
        return Wallets::where('user_id' , $user_id)->firstOrFail();
    }

    public function transferBetweenWallets(int|string $payerId, int|string $payeeId, int $amount): array
    {
        $payerWallet = $this->findWalletByUserId($payerId);
        $payeeWallet = $this->findWalletByUserId($payeeId);

        $payerWallet->balance -= $amount;
        $payeeWallet->balance += $amount;

        $payerWallet->save();
        $payeeWallet->save();

        return [$payerWallet->refresh(), $payeeWallet->refresh()];
    }
}
