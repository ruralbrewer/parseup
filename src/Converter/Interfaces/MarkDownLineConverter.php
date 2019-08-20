<?php
declare(strict_types=1);

namespace ParseUp\Converter\Interfaces;

use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

interface MarkDownLineConverter
{
    public function type(): EntityType;

    public function canConvert(Line $line): bool;

    public function convert(Line $line, BlockStack $blockStack, &$html = []);

    public function isEndOfBlock(): bool;
}