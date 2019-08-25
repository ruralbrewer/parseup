<?php
declare(strict_types=1);

namespace ParseUp\ValueObject;

class Line
{
    /**
     * @var string
     */
    private $line = '';

    public function __construct(string $line)
    {
        $this->line = preg_replace('/ {4}(?!\R+)/', "\t", $line);
    }

    public function setLine(string $line): void
    {
        $this->line = $line;
    }

    public function asString(): string
    {
        return $this->line;
    }

    public function getIndentionLevel(): int
    {
        preg_match('/^\>?(\t*)/', $this->line, $matches);

        return strlen($matches[1]);
    }

    public function reduceIndentionBy(int $numberOfTabs): void
    {
        $this->line = preg_replace('/^>?(\t{'.$numberOfTabs.'})/', '', $this->line);
    }

    public function matches(string $pattern): bool
    {
        return (bool) preg_match($pattern, $this->line);
    }

    public function getMatches(string $pattern): array
    {
        preg_match($pattern, $this->line, $matches);

        return $matches;
    }

    public function getAllMatches(string $pattern): array
    {
        preg_match_all($pattern, $this->line, $matches);

        return $matches;
    }

    public function replace(string $pattern, string $replacement): void
    {
        $this->line = preg_replace($pattern, $replacement, $this->line);
    }

    public function replaceWithCallBack(string $pattern, callable $callback): void
    {
        $this->line = preg_replace_callback($pattern, $callback, $this->line);
    }

    public function htmlEntities(): void
    {
        $this->line = htmlentities($this->line);
    }
}