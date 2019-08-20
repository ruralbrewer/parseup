<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class BoldItalicCloserConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::boldItalicCloser();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/[^\s|\*]([\*_]{3})/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/([^\s|\*])([\*_]{3})/', '$1</em></strong>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}