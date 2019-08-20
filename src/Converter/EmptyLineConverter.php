<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class EmptyLineConverter implements MarkDownLineConverter
{
    public function type(): EntityType
    {
        return EntityType::emptyLine();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/^\s*$/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        if (
            $blockStack->peek()->equals(EntityType::paragraph()) ||
            $blockStack->peek()->equals(EntityType::blockQuote())
        ) {
            if ($blockStack->peek()->equals(EntityType::paragraph())) {
                $blockStack->pop();
            }
            $line->setLine('</p>');

            return;
        }

        $line->setLine('');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}