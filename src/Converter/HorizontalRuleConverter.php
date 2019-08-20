<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class HorizontalRuleConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::horizontalRule();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/[\*|_|-]{3}/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/[\*|_|-]{3}/', '<hr/>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}