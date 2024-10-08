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

use App\Exception\Handler\AppExceptionHandler;
use App\Exception\Handler\ServiceExceptionHandler;

return [
    'handler' => [
        'http' => [
            ServiceExceptionHandler::class,
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            AppExceptionHandler::class,
        ],
    ],
];
