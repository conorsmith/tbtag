<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use InvalidArgumentException;

class CommandRepository
{
    private $commands;

    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    public function find(Input $input)
    {
        if (!array_key_exists(strval($input), $this->commands)) {
            throw new InvalidArgumentException;
        }

        return $this->commands[strval($input)];
    }

    public function getUnique(): array
    {
        return array_unique(array_values($this->commands));
    }
}
