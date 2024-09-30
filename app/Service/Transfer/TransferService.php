<?php
declare(strict_types=1);

namespace App\Service\Transfer;

use App\ExternalServices\TransactionNotificator\TransactionNotificator;
use App\ExternalServices\TransactionValidator\TransactionValidator;
use App\Model\Users;
use App\Repositories\Wallets\WalletsEloquentRepository;
use App\Resources\Transfer\ExecuteTransferResource;
use Hyperf\DbConnection\Db;
use function Hyperf\Coroutine\co;

class TransferService
{
    public function executeTransfer(Users $payee, Users $payer, int $amount): ExecuteTransferResource
    {
        try {
            Db::beginTransaction();
            [$payerWallet, $payeeWallet] = WalletsEloquentRepository::instantiate()
                ->transferBetweenWallets(
                    $payer->id,
                    $payee->id,
                    $amount
                );

            TransactionValidator::instantiate()->validate();
            Db::commit();
        } catch (\Throwable $exception) {
            Db::rollBack();
            throw $exception;
        } finally {
            co(fn() => TransactionNotificator::instantiate()->notify());
        }

        return ExecuteTransferResource::make([
            'payerWallet' => $payerWallet,
            'payeeWallet' => $payeeWallet
        ]);
    }
}
