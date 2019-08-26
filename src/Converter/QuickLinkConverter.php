<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class QuickLinkConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::quickLink();
    }

    public function canConvert(Line $line): bool
    {
        $matches = $line->getMatches('/<(.*)>/');

        return (!empty($matches[1]) && filter_var($matches[1], FILTER_VALIDATE_URL) !== false);
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/<(.*)>/', '<a href="$1">$1</a>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}