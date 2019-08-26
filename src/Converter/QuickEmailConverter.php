<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class QuickEmailConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::quickEmail();
    }

    public function canConvert(Line $line): bool
    {
        $matches = $line->getMatches('/<(.*)>/');

        return (!empty($matches[1]) && filter_var($matches[1], FILTER_VALIDATE_EMAIL) !== false);
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/<(.*)>/', '<a href="mailto:$1">$1</a>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}