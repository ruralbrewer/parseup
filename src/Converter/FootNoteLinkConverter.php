<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class FootNoteLinkConverter implements MarkDownLineConverter
{
    /**
     * @var array
     */
    private $matches;

    public function type(): EntityType
    {
        return EntityType::footNoteLink();
    }

    public function canConvert(Line $line): bool
    {
        $this->matches = $line->getMatches('/\[\^([^\s]+)\]/');

        return !empty($this->matches[0]);
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $line->replace('/\[\^([^\s]+)\]/', '<sup><a id="$1" href="#footnote-$1">$1</a></sup>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}