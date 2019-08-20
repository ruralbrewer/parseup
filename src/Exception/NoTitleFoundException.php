<?php
declare(strict_types=1);

namespace ParseUp\Exception;

class NoTitleFoundException extends MarkDownParserException
{
    public function __construct()
    {
        parent::__construct("Looking for H1 tag (#), but none found.");
    }
}