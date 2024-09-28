<?php

namespace App\ExternalServices\TransactionValidator;

interface TransactionValidatorInterface
{
    public function validate(): void;
}
