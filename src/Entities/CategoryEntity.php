<?php

declare(strict_types=1);

namespace Buyme\Parser\Entities;

use ArrayObject;

class CategoryEntity extends ArrayObject
{
    public function getChildren(): array
    {
        return [];
    }
    public function getParentCategory(): CategoryEntity|null
    {
        return new self([]);
    }
    public function hasParentCategory(): bool
    {
        return true;
    }
}