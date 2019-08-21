<?php
declare(strict_types=1);

namespace ParseUp;

use ParseUp\ValueObject\File;

interface MarkDownParser
{
    public function loadFile(File $markDown);

    public function getTitle(): string;

    public function convert(): string;

}