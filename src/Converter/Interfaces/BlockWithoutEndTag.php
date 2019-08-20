<?php
declare(strict_types=1);

namespace ParseUp\Converter\Interfaces;

interface BlockWithoutEndTag
{
    public function endTag(): string;
}