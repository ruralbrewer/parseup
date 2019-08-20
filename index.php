<?php

use ParseUp\Factory;
use ParseUp\ValueObject\File;

require 'vendor/autoload.php';

$factory = new Factory();

$parser = $factory->createParser();

try {

    $file = new File(__DIR__ . '/mdfiles/001-main.md');

    $parser->loadFile($file);

    $html = $parser->convert();

    echo $html;

}
catch(\Throwable $exception) {
    echo $exception->getMessage();
}

$executionTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
echo "This process took $executionTime seconds\n";