<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Requests\Transfer\TransferRequest;
use App\Resources\Transfer\ExecuteTransferResource;
use App\Service\Transfer\TransferService;

class TransferController extends AbstractController
{
    public function __construct(private TransferService $transferService)
    {
    }

    public function transfer(TransferRequest $request): ExecuteTransferResource
    {
        return $this->transferService->executeTransfer(
            payee: $request->getPayee(),
            payer: $request->getPayer(),
            amount: $request->getAmount()
        );
    }
}
