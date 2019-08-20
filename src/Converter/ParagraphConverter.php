<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class ParagraphConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::paragraph();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/.*/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $tag = '';

        if (
            $blockStack->isEmpty() ||
            $blockStack->peek()->equals(EntityType::blockEnd())
        ) {

            $tag = '<p>';

            if ($blockStack->peek()->equals(EntityType::blockEnd())) {
                $blockStack->pop();
            }

            $blockStack->push(EntityType::paragraph());
        }

        $line->setLine($tag . $line->asString());
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}