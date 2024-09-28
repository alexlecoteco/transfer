<?php
declare(strict_types=1);

namespace App\Service\Transfer;

use App\ExternalServices\TransactionNotificator\TransactionNotificator;
use App\ExternalServices\TransactionValidator\TransactionValidator;
use App\Model\Users;
use App\Model\Wallets;
use App\Resources\Transfer\ExecuteTransferResource;
use Hyperf\DbConnection\Db;
use function Hyperf\Coroutine\co;

class TransferService
{
    public function executeTransfer(Users $payee, Users $payer, int $amount): ExecuteTransferResource
    {
        try {
            $payeeWallet = Wallets::where('user_id', $payee->id)->firstOrFail();
            $payerWallet = Wallets::where('user_id', $payer->id)->firstOrFail();

            $payerWallet->balance -= $amount;
            $payeeWallet->balance += $amount;

            Db::beginTransaction();
            $payerWallet->save();
            $payeeWallet->save();

            TransactionValidator::instantiate()->validate();
            Db::commit();
        } catch (\Throwable $exception) {
            Db::rollBack();
            throw $exception;
        } finally {
            co(fn() => TransactionNotificator::instantiate()->notify());
        }

        return ExecuteTransferResource::make([
            'payerWallet' => $payerWallet->refresh(),
            'payeeWallet' => $payeeWallet->refresh()
        ]);
    }
}
