<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class EscapedCharactersConverter implements MarkDownLineConverter
{
    /**
     * @var array
     */
    private $escapedCharactersMap = [
        '\\\\' => '&#92;',
        '\`' => '&#96;',
        '\*' => '&#42;',
        '\_' => '&#95;',
        '\{' => '&#123;',
        '\}' => '&#125;',
        '\[' => '&#91;',
        '\]' => '&#93;',
        '\(' => '&#40;',
        '\)' => '&#41;',
        '\#' => '&#35;',
        '\+' => '&#43;',
        '\-' => '&#8208;',
        '\.' => '&#46;',
        '\!' => '&#33;',
        '\|' => '&#124;'
    ];

    public function type(): EntityType
    {
        return EntityType::escapedCharacters();
    }

    public function canConvert(Line $line): bool
    {
        return $line->matches('/(\\\\[\\\`\*\_\{\}\[\]\(\)\#\+\-\.\!\|])/');
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $characterMap = $this->escapedCharactersMap;

        $line->replaceWithCallBack('/(\\\\[\\\`\*\_\{\}\[\]\(\)\#\+\-\.\!\|])/', function($match) use ($characterMap) {
            return $characterMap[$match[1]];
        });
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}