<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class InlineBoldConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::inlineBold();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/[\*_]{2}([^\s|\*].*[^\s|\*])[\*_]{2}/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/[\*_]{2}([^\s|\*].*[^\s|\*])[\*_]{2}/', '<strong>$1</strong>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}