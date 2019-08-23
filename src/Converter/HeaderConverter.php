<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class HeaderConverter implements MarkDownLineConverter
{

    public function type(): EntityType
    {
        return EntityType::header();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/^[#]{1,6}\s*/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $matches = $line->getMatches('/^([#]{1,6})\s*([^\{]*)\s*(\{(.*)\})?/');

        $id = (!empty($matches[3])) ? ' id="'. $matches[4] . '"' : '';

        $level = strlen($matches[1]);

        $headerLine = '';

        if (
            $blockStack->peek()->equals(EntityType::paragraph())) {
            $headerLine = '</p>';
            $blockStack->pop();
        }

        $blockStack->push(EntityType::blockEnd());

        $line->setLine($headerLine . '<h' . $level .  $id . '>' . $matches[2] . '</h' . $level . '>');
    }

    public function isEndOfBlock(): bool
    {
        return true;
    }
}