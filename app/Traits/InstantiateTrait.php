<?php

namespace App\Traits;

use function Hyperf\Support\make;

trait InstantiateTrait
{
    public static function instantiate(): self
    {
        return make(self::class);
    }
}
