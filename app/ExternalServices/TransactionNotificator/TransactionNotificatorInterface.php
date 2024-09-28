<?php

namespace App\ExternalServices\TransactionNotificator;

interface TransactionNotificatorInterface
{
    public function notify(): bool;
}
