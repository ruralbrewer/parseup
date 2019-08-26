<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class TaskListConverter implements MarkDownLineConverter
{
    /**
     * @var array
     */
    private $matches;

    public function type(): EntityType
    {
        return EntityType::taskList();
    }

    public function canConvert(Line $line): bool
    {
        $this->matches = $line->getMatches('/^\s{0,3}- \[([\sxX])\]\s{1,3}(.*)/');

        return !empty($this->matches[0]);
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        $checked = ($this->matches[1] != ' ') ? 'checked' : '';

        $line->setLine('<input type="checkbox" ' . $checked . '> ' . $this->matches[2] . '<br/>');
    }

    public function isEndOfBlock(): bool
    {
        return false;
    }
}