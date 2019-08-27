<?php
declare(strict_types=1);

namespace ParseUp\Converter\Interfaces;

interface HighLighter
{
    public function setHighlighterPrefix(string $prefix): void;
}