<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use InvalidArgumentException;

class Dispatcher
{
    /** @var CommandRepository */
    private $commands;

    public function __construct(CommandRepository $commands)
    {
        $this->commands = $commands;
    }

    public function __invoke(string $commandClass, array $args)
    {
        if ($commandClass === ExitCommand::class)
        {
            return new ExitCommand;
        }

        if ($commandClass === HelpCommand::class) {
            return new HelpCommand($this->commands->getCommands());
        }

        if ($commandClass === LookCommand::class) {
            return new LookCommand;
        }

        if ($commandClass === MoveCommand::class && count($args) === 1) {
            return new MoveCommand(new Direction($args[0]));
        }

        throw new InvalidArgumentException;
    }
}
