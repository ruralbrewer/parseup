<?php
declare(strict_types=1);

namespace ParseUp\Utility;

use ParseUp\ValueObject\EntityType;

class BlockStack
{
    private $stack = [];

    public function push(EntityType $entityType): void
    {
        $this->stack[] = $entityType;
    }

    public function pop(): EntityType
    {
        if (empty($this->stack)) {
            return EntityType::null();
        }

        return array_pop($this->stack);
    }

    public function peek(): EntityType
    {
        if (empty($this->stack)) {
            return EntityType::null();
        }

        return end($this->stack);
    }

    public function size(): int
    {
        return count($this->stack);
    }

    public function isEmpty(): bool
    {
        return empty($this->stack);
    }

    public function asArray(): array
    {
        return $this->stack;
    }
}