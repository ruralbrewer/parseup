<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class ItalicOpenerConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::italicOpener();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/([\*_])[^\s|\*]/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/([\*_])(?=[^\s|\*])/', '<em>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}