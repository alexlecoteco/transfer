<?php

namespace App\Enums;

enum UserTypesEnum: string
{
    case COMMON = 'comum';
    case LOJIST = 'lojista';


    public static function availableCases(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
