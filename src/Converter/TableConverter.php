<?php
declare(strict_types=1);

namespace ParseUp\Converter;

use ParseUp\Converter\Interfaces\BlockWithoutEndTag;
use ParseUp\Converter\Interfaces\MarkDownLineConverter;
use ParseUp\Converter\Interfaces\Nestable;
use ParseUp\Utility\BlockStack;
use ParseUp\ValueObject\EntityType;
use ParseUp\ValueObject\Line;

class TableConverter implements MarkDownLineConverter, BlockWithoutEndTag, Nestable
{
    /**
     * @var array
     */
    private $matches = [];

    /**
     * @var array
     */
    private $tHeadMatches = [];

    /**
     * @var array
     */
    private $alignment = [];

    public function type(): EntityType
    {
        return EntityType::table();
    }

    public function canConvert(Line $line): bool
    {
        $this->matches = $line->getAllMatches('/\|\s*([^\|]+)+\s*/');

        return !empty($this->matches[0]);
    }

    public function convert(Line $line, BlockStack $blockStack, &$html = [])
    {
        if (!$blockStack->peek()->equals(EntityType::table())) {
            $html[] = '<table><thead><tr>';
            $blockStack->push(EntityType::table());

            $tHead = '';

            foreach($this->matches[1] as $tHeadText) {

                $tHeadText = rtrim($tHeadText);
                if (!empty($tHeadText)) {
                    $tHead .= '<th>' . $tHeadText . '</th>';
                }
            }

            $line->setLine($tHead . '</tr></thead>');

            $this->tHeadMatches = $this->matches;
        }
        else {

            $matches = $line->getAllMatches('/\|\s*([\:-]+)+\s*/');

            if (!empty($matches[0])) {

                $this->alignment = [];

                foreach ($matches[1] as $alignment) {

                    $align = 'center';

                    switch ($alignment) {
                        case (preg_match('/\:-+\:/', $alignment) ? true : false):
                               break;
                        case (preg_match('/\:-+/', $alignment) ? true : false):
                               $align = 'left';
                               break;
                        case (preg_match('/-+\:/', $alignment) ? true : false):
                               $align = 'right';
                               break;
                    }

                    $this->alignment[] = $align;
                }

                array_pop($html);

                $tHead = '';

                foreach($this->tHeadMatches[1] as $index => $tHeadText) {

                    $tHeadText = rtrim($tHeadText);

                    if (!empty($tHeadText)) {

                        $tHead .= '<th align="' . $this->alignment[$index] . '">' . $tHeadText . '</th>';

                    }
                }

                $line->setLine($tHead . '</tr></thead><tbody>');
            }
            else {

                $row = '<tr>';

                foreach ($this->tHeadMatches[1] as $index => $column) {

                    if (!preg_match('/^[\s]*$/', $column)) {

                        $tdText = isset($this->matches[1][$index]) ? $this->matches[1][$index] : '';

                        $align = (isset($this->alignment[$index])) ? $this->alignment[$index] : 'center';

                        $row .= '<td align="' . $align . '">' . $tdText . '</td>';

                    }
                }

                $line->setLine($row . '</tr>');
            }
        }

    }

    public function isEndOfBlock(): bool
    {
        return false;
    }

    public function endTag(): string
    {
        return '</tbody></table>';
    }

    public function currentIndentionLevel(): int
    {
        return 0;
    }

    public function isNesting(): bool
    {
        false;
    }

    public function setIsNesting()
    {
        false;
    }
}