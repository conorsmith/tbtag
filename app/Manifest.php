<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Manifest
{
    public static function unoccupied(): self
    {
        return new self([]);
    }

    private $contents;

    public function __construct(array $contents)
    {
        $this->contents = $contents;
    }

    public function has(string $slug): bool
    {
        foreach ($this->contents as $content) {
            if (strval($content) === $slug) {
                return true;
            }
        }

        return false;
    }
}
