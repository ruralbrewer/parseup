<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\Utility\EntityTypeCollection;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class FootNoteConverter implements MarkDownLineConverter
{
    /**
     * @var array
     */
    private $matches;

    /**
     * @var int
     */
    private $footnoteCount = 1;

    public function type(): EntityType
    {
        return EntityType::footNote();
    }

    public function canConvert(Line $line): bool
    {
        $this->matches = $line->getMatches('/\[\^([^\s]+)\]:\s*(.*)/');

        return !empty($this->matches);
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $opener = '';

        if ($blockStack->peek()->equals(EntityType::paragraph())) {
            $opener = '</p>';
        }
        else {
            $blockStack->push(EntityType::paragraph());
        }

        $anchor = '<a id="footnote-$1" class="footnote">[' . $this->footnoteCount . '.]</a>';
        $line->replace('/\[\^([^\s]+)\]:\s*(.*)/', $opener . $anchor . '$2 <a href="#$1">$1</a><br/>');
        $this->footnoteCount++;
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}