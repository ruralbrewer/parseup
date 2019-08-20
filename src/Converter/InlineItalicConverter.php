<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class InlineItalicConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::inlineItalic();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/[\*_]([^\s|\*].*[^\s|\*])[\*_]/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/[\*_]([^\s|\*].*[^\s|\*])[\*_]/', '<em>$1</em>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}