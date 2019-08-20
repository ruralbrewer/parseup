<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\BlockWithoutEndTag;
use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Converter\Interfaces\Nestable;
use ParseUp\Utility\BlockStack;
use ParseUp\Utility\EntityTypeCollection;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class IndentedCodeBlockConverter implements MarkDownLineConverter, Nestable, BlockWithoutEndTag
{
    /**
     * @var EntityTypeCollection
     */
    private $blocksToIgnore;

    private $currentIndentionLevel = 0;

    private $isNesting = false;

    public function __construct()
    {
        $blocksToIgnore = new EntityTypeCollection();
        $blocksToIgnore->addEntityType(EntityType::orderedList());
        $blocksToIgnore->addEntityType(EntityType::unorderedList());
        $blocksToIgnore->addEntityType(EntityType::blockQuote());

        $this->blocksToIgnore = $blocksToIgnore;
    }


    public function type(): EntityType
    {
        return EntityType::indentedCode();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/(^\t+).*/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        if (!$this->blocksToIgnore->has($blockStack->peek())) {

            if (!$blockStack->peek()->equals(EntityType::indentedCode())) {

                $tag = $this->checkForParagraph($blockStack);

                $tag = $tag . '<pre><code>';

                $blockStack->push(EntityType::indentedCode());

                $line->htmlEntities();

                $line->replace('/(^\t)/', $tag . "\n");

            } else {
                $line->replace('/(^\t)/', '');
            }
        }
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
        return '</code></pre>';
    }

    private function checkForParagraph(BlockStack $blockStack)
    {
        $tag = '';

        if ($blockStack->peek()->equals(EntityType::paragraph())) {
            $tag = '</p>';
            $blockStack->pop();
        }

        return $tag;
    }
}