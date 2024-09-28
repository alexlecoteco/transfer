<?php
declare(strict_types=1);

namespace App\Requests\Transfer;

use App\Model\Users;
use App\Model\Wallets;
use App\Requests\BaseRequest;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;

class TransferRequest extends BaseRequest
{
    protected function rules(): array
    {
        return [
            'payee' => ['required'],
            'payer' => ['required'],
            'amount' => ['required'],
        ];
    }

    public function getPayee(): Users|Collection|Model|array
    {
        return Users::findOrFail($this->input('payee'));
    }

    public function getPayer(): Users|Collection|Model|array
    {
        //TODO Payee must not be lojista
        return Users::findOrFail($this->input('payer'));
    }

    public function getAmount(): int
    {
        $amount = $this->input('amount');

        if (!is_numeric($amount)) {
            throw new \Exception('Amount must be an integer');
        }

        if ($amount < 0) {
            throw new \Exception('Amount must be greater than 0');
        }

        $payeeWallet = Wallets::where('user_id', $this->input('payee'))->firstOrFail();
        if ($payeeWallet->balance < $amount) {
            throw new \Exception('Payee does not have enough balance');
        }

        return $this->input('amount');
    }
}
