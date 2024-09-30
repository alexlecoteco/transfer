<?php
declare(strict_types=1);

namespace App\Requests\Transfer;

use App\Enums\UserTypesEnum;
use App\Model\Users;
use App\Repositories\Users\UsersEloquentRepository;
use App\Repositories\UserTypes\UserTypesEloquentRepository;
use App\Repositories\Wallets\WalletsEloquentRepository;
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
        return UsersEloquentRepository::instantiate()->findUserOrFail($this->input('payee'));
    }

    public function getPayer(): Users|Collection|Model|array
    {
        $payer = UsersEloquentRepository::instantiate()->findUserOrFail($this->input('payer'));

        $userType = UserTypesEloquentRepository::instantiate()
            ->findUserTypeOrFail($payer->user_type);

        if ($userType->name === UserTypesEnum::LOJIST->value) {
            throw new \Exception('Payer must not be lojista');
        }

        return $payer;
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

        $payeeWallet = WalletsEloquentRepository::instantiate()->findWalletByUserId($this->input('payer'));
        if ($payeeWallet->balance < $amount) {
            throw new \Exception('Payer does not have enough balance');
        }

        return $this->input('amount');
    }
}
