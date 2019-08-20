<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class CodeBlockConverter implements MarkDownLineConverter
{
    private $isBlockEnd = false;

    public function type(): EntityType
    {
        return EntityType::code();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/[\~`]{3}.*/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $tag = $this->checkForParagraph($blockStack);

        $tag = $tag . '<pre><code>';

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
}