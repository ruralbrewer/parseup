<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\BlockWithoutEndTag;
use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Converter\Interfaces\Nestable;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class BlockQuoteConverter implements MarkDownLineConverter, Nestable, BlockWithoutEndTag
{
    private $currentIndentionLevel = 0;

    private $isNesting = false;

    public function type(): EntityType
    {
        return EntityType::blockQuote();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/^>+/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $matches = $line->getMatches('/^(>+)/');

        $indentionLevel = strlen($matches[1]);

        $prefix = $this->checkForParagraph($blockStack);

        $line->replace('/^>+/', '');

        if ($this->shouldOpenNewBlockQuote($blockStack, $indentionLevel)) {

            $this->currentIndentionLevel = $indentionLevel;

            $blockStack->push(EntityType::blockQuote());

            $prefix = $prefix . '<blockquote><p>';
        }
        else if ($indentionLevel < $this->currentIndentionLevel) {

            $blockStack->pop();

            if ($blockStack->peek()->equals(EntityType::nested())) {
                $blockStack->pop();
            }

            $levelDifference = ($this->currentIndentionLevel - $indentionLevel);

            $this->currentIndentionLevel = $indentionLevel;

            for ($i = 1; $i <= $levelDifference; $i++) {
                $prefix .= '</p></blockquote>';
            }
        }

        $line->setLine($prefix . $line->asString());
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }

    public function currentIndentionLevel(): int
    {
        return $this->currentIndentionLevel;
    }

    public function isNesting(): bool
    {
        return $this->isNesting;
    }

    public function setIsNesting()
    {
        $this->isNesting = true;
    }

    public function endTag(): string
    {
        return '</p></blockquote>';
    }

    private function checkForParagraph(BlockStack $blockStack): string
    {
        $prefix = '';

        if ($blockStack->peek()->equals(EntityType::paragraph())) {
            $prefix = '</p>';
        }

        if ($blockStack->peek()->equals(EntityType::blockEnd())) {
            $blockStack->pop();
            $prefix = '<p>';
        }

        return $prefix;
    }

    private function shouldOpenNewBlockQuote(BlockStack $blockStack, int $indentionCount): bool
    {
        return (
            (!$blockStack->peek()->equals(EntityType::blockQuote()) &&
            !$blockStack->peek()->equals(EntityType::unorderedList()) &&
            !$blockStack->peek()->equals(EntityType::orderedList())) ||
            $indentionCount > $this->currentIndentionLevel
        );
    }
}