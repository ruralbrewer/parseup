<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\HighLighter;
use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class CodeBlockConverter implements MarkDownLineConverter, HighLighter
{
    /**
     * @var array
     */
    private $matches = [];

    /**
     * @var bool
     */
    private $isBlockEnd = false;

    /**
     * @var string
     */
    private $highlighterPrefix = '';

    public function type(): EntityType
    {
        return EntityType::code();
    }

    public function canConvert(Line $line): bool
    {
        $this->matches = $line->getMatches('/[\~`]{3}([a-z]*)\s.*/');

        return !empty($this->matches[0]);
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $tag = $this->checkForParagraph($blockStack);

        $opener = (!empty($this->matches[1])) ? '<pre><code class="' . $this->highlighterPrefix . $this->matches[1] .'">' : '<pre><code>';

        $tag = $tag . $opener;

        if ($blockStack->peek()->equals(EntityType::code())) {
            $tag = '</code></pre>';
            $blockStack->pop();
            $blockStack->push(EntityType::blockEnd());
            $this->isBlockEnd = true;
        }
        else {
            $blockStack->push(EntityType::code());
            $this->isBlockEnd = false;
        }

        $line->replace('/[\~`]{3}.*/', $tag);
    }

    public function isEndOfBlock(): bool
    {
        return $this->isBlockEnd;
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

    public function setHighlighterPrefix(string $prefix): void
    {
        $this->highlighterPrefix = $prefix . '-';
    }
}