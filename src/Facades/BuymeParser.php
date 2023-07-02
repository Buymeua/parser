<?php

declare(strict_types=1);

namespace Buyme\Parser\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see BaseByml
 */
class BuymeParser extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-buyme-parser';
    }
}