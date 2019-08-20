<?php
declare(strict_types=1);

namespace ParseUp\ValueObject;

use ParseUp\Exception\InvalidFileException;

class File
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $pathInfo = [];


    /**
     * @throws InvalidFileException
     */
    public function __construct(string $path)
    {
        $this->ensureIsValidFile($path);
        $this->path = $path;
    }

    /**
     * @throws InvalidFileException
     */
    private function ensureIsValidFile(string $path): void
    {
        if (!is_file($path)) {
            throw new InvalidFileException(
                sprintf('%s is not a valid file path.', $path)
            );
        }
    }

    public function path(): string
    {
        return $this->path;
    }

    public function name(): string
    {
        if (empty($this->pathInfo)) {
            $this->pathInfo = pathinfo($this->path);
        }

        return $this->pathInfo['basename'];
    }

    public function lines(): array
    {
        return file($this->path);
    }

    public function asString(): string
    {
        return file_get_contents($this->path);
    }
}