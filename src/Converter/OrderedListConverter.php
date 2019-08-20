<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\BlockWithoutEndTag;
use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Converter\Interfaces\Nestable;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class OrderedListConverter implements MarkDownLineConverter, Nestable, BlockWithoutEndTag
{
    private $currentIndentionLevel = 0;

    private $isNesting = false;

    public function type(): EntityType
    {
        return EntityType::orderedList();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/^>?([\t|\s]*)\d\.\s.*/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $indentionLevel = $line->getIndentionLevel();

        $line->replace('/^>?(?:[\s]*)\d\.\s(.*)/', '<li>$1</li>');

        $prefix = $this->checkForParagraph($blockStack);

        if ($blockStack->peek()->equals(EntityType::nested())) {
            $blockStack->pop();
            $this->isNesting = false;
        }

        if ($this->shouldOpenNewListBlock($blockStack, $indentionLevel)) {

            $this->currentIndentionLevel = $indentionLevel;

            $blockStack->push(EntityType::orderedList());

            $prefix = $prefix . '<ol>';
        }
        else if ($indentionLevel < $this->currentIndentionLevel) {

            $blockStack->pop();

            $levelDifference = ($this->currentIndentionLevel - $indentionLevel);

            $this->currentIndentionLevel = $indentionLevel;

            for ($i = 1; $i <= $levelDifference; $i++) {
                $prefix .= '</ol>';
            }
        }

        $line->setLine($prefix . $line->asString());
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }

    public function endTag(): string
    {
        return '</ol>';
    }

    public function currentIndentionLevel(): int
    {
        return $this->currentIndentionLevel;
    }

    public function setIsNesting()
    {
        $this->isNesting = true;
    }

    public function isNesting(): bool
    {
        return $this->isNesting;
    }

    private function checkForParagraph(BlockStack $blockStack)
    {
        $prefix = '';

        if ($blockStack->peek()->equals(EntityType::paragraph())) {
            $prefix = '</p>';
            $blockStack->pop();
        }

        return $prefix;
    }

    private function shouldOpenNewListBlock(BlockStack $blockStack, int $indentionLevel): bool
    {
        return (
            !$blockStack->peek()->equals(EntityType::orderedList()) ||
            $indentionLevel > $this->currentIndentionLevel
        );
    }
}