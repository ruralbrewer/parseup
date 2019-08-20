<?php
declare(strict_types=1);

namespace ParseUp;

use ParseUp\Exception\MarkDownParserException;
use ParseUp\ValueObject\File;

interface MarkDownParser
{
    public function loadFile(File $markDown);

    public function getTitle(): string;

    /**
     * @throws MarkDownParserException
     */
    public function convert(): string;

    public function linesProcessed(): int;

}