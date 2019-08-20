<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class InlineCodeBlockConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::inlineCode();
    }

    public function canConvert(Line $line): bool
    {
        $line->replace('/(?<=[^`])[`]{2}(?=[^`]).*/', '');

        return $line->matches('/((?<![\~`])[\~`]([^\~`]+)[\~`]|(?<![\~`])[\~`]{3}([^\~`]*)[\~`]{3})/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/((?<![\~`])[\~`]([^\~`]+)[\~`]|(?<![\~`])[\~`]{3}([^\~`]*)[\~`]{3})/', '<code>$2</code>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}