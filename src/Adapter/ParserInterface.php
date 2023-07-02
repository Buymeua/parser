<?php

declare(strict_types=1);

namespace Buyme\Parser\Adapter;

use Closure;
use Generator;
use Iterator;

interface ParserInterface
{
    public function open(string $xml);
    public function getCategories();
    public function getProducts(Closure $filter = null): Iterator;
    public function getCurrencies();
    public function countProducts(Closure $filter): int;
}