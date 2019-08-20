<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class LinkConverter implements MarkDownLineConverter
{
    /**
     * @var array
     */
    private $matches = [];

    public function type(): EntityType
    {
        return EntityType::link();
    }

    public function canConvert(Line $line): bool
    {
        $matches = $line->getMatches('/(?<!!)\[(.*)]\(([^ "\']+)([ ]["\']([^"\'\]]+)["\'])?\)/');

        $this->matches = $matches;

        return !empty($matches);
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $tag = (isset($this->matches[4]))? '<a title="$4" href="$2">$1</a>' : '<a href="$2">$1</a>';

        $line->replace('/\[(.*)]\(([^ "\']+)([ ]["\']([^"\'\]]+)["\'])?\)/', $tag);
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}