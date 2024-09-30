<?php

namespace App\Repositories\Wallets;

interface WalletRepositoryInterface
{
    public function createWallet(int $user_id, int $balance);

    public function findWalletByUserId(int $user_id);

    public function transferBetweenWallets(int|string $payerId, int|string $payeeId, int $amount);
}
