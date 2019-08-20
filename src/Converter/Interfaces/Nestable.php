<?php
declare(strict_types=1);

namespace ParseUp\Converter\Interfaces;

interface Nestable
{
    public function currentIndentionLevel(): int;

    public function isNesting(): bool;

    public function setIsNesting();
}