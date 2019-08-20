<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class ImageConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::image();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/!\[([^]]*)]\(([^)]*)\)/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/!\[([^]]*)]\(([^)]*)\)/', '<img src="$2" alt="$1">');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}