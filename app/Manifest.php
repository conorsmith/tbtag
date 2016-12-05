<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;

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

    public function has(AutomatonIdentifier $identifier): bool
    {
        foreach ($this->contents as $content) {
            if (strval($content) === strval($identifier)) {
                return true;
            }
        }

        return false;
    }

    public function processInterceptions(Command $command): bool
    {
        $commandWasIntercepted = false;

        foreach ($this->contents as $content) {
            if ($content instanceof Interactive) {
                $commandWasIntercepted = $commandWasIntercepted || $content->intercept($command);
            }
        }

        return $commandWasIntercepted;
    }
}
