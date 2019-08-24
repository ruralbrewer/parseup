<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class StrikeThroughConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::strikeThrough();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/~{2}(.+)~{2}/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/~{2}(.+)~{2}/', '<s>$1</s>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}