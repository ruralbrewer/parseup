<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class UnderlinedHeaderConverter implements MarkDownLineConverter
{

    /**
     * @var array
     */
    private $matches = [];

    public function type(): EntityType
    {
        return EntityType::underlinedHeader();
    }

    public function canConvert(Line $line): bool
    {
        $this->matches = $line->getMatches('/^\s*((==)+|(--)+)/');

        return !empty($this->matches);
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $lastLine = array_pop($html);

        $openTag = '';

        if (preg_match('/^<p>(.*)/', $lastLine, $paragraphMatch)) {
            $lastLine = $paragraphMatch[1];
        }
        else if ($blockStack->peek()->equals(EntityType::paragraph())) {
            $openTag = '</p>';
        }

        $openTag .= ($this->matches[1] == "==") ? '<h1>' : '<h2>';
        $closeTag = ($this->matches[1] == "==") ? '</h1>' : '</h2>';

        $line->setLine($openTag . $lastLine . $closeTag);

    }

    public function isEndOfBlock(): bool
    {
        return true;
    }
}