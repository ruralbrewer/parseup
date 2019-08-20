<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class LineBreakConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::lineBreak();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/(?:.*)( {2,}\R+)/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/(.*)( {2,}\R+)/', '$1<br />');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}