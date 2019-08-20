<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\BlockWithoutEndTag;
use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Converter\Interfaces\Nestable;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class UnorderedListConverter implements MarkDownLineConverter, Nestable, BlockWithoutEndTag
{
    private $currentIndentionLevel = 0;

    private $isNesting = false;

    public function type(): EntityType
    {
        return EntityType::unorderedList();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/^>?([\t| ]*)[-+*]\s.*/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $indentionLevel = $line->getIndentionLevel();

        $line->replace('/^>?(?:[\s]*)[-+*]\s(.*)/', '<li>$1</li>');

        $prefix = $this->checkForParagraph($blockStack);

        if ($blockStack->peek()->equals(EntityType::nested())) {
            $blockStack->pop();
            $this->isNesting = false;
        }

        if ($this->shouldOpenNewListBlock($blockStack, $indentionLevel)) {

            $this->currentIndentionLevel = $indentionLevel;

            $blockStack->push(EntityType::unorderedList());

            $prefix = $prefix . '<ul>';
        }
        else if ($indentionLevel < $this->currentIndentionLevel) {

            $blockStack->pop();

            $levelDifference = ($this->currentIndentionLevel - $indentionLevel);

            $this->currentIndentionLevel = $indentionLevel;

            for ($i = 1; $i <= $levelDifference; $i++) {
                $prefix .= '</ul>';
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
        return '</ul>';
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

        if (
            $blockStack->peek()->equals(EntityType::paragraph()) ||
            $blockStack->peek()->equals(EntityType::blockQuote())
        ) {
            $prefix = '</p>';
        }

        return $prefix;
    }

    private function shouldOpenNewListBlock(BlockStack $blockStack, int $indentionCount): bool
    {
        return (
            !$blockStack->peek()->equals(EntityType::unorderedList()) ||
            $indentionCount > $this->currentIndentionLevel
        );
    }
}