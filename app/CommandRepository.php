<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use InvalidArgumentException;

class CommandRepository
{
    private $commands;

    public function __construct(array $commands)
    {
        $this->commands = collect($commands)
            ->keyBy(function ($command) {
                return $command::SLUG;
            })
            ->toArray();
    }

    public function getSlugs()
    {
        return array_keys($this->commands);
    }

    public function getCommands()
    {
        return $this->commands;
    }

    public function find(string $commandSlug)
    {
        if (!array_key_exists($commandSlug, $this->commands)) {
            throw new InvalidArgumentException;
        }

        return $this->commands[$commandSlug];
    }
}
