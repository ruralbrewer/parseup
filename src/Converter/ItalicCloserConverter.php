<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class ItalicCloserConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::italicCloser();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/[^\s|\*]([\*_])/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/([^\s|\*])([\*_])/', '$1</em>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}