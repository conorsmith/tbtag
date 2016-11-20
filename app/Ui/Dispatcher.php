<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\DirectionFactory;
use ConorSmith\Tbtag\ExitCommand;
use ConorSmith\Tbtag\HelpCommand;
use ConorSmith\Tbtag\LookCommand;
use ConorSmith\Tbtag\MoveCommand;
use InvalidArgumentException;

class Dispatcher
{
    /** @var CommandRepository */
    private $commands;

    /** @var DirectionFactory */
    private $directionFactory;

    public function __construct(CommandRepository $commands, DirectionFactory $directionFactory)
    {
        $this->commands = $commands;
        $this->directionFactory = $directionFactory;
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

        if ($commandClass === MoveCommand::class) {
            if (count($args) !== 1) {
                throw new MissingArgument("Where do you want to go?");
            }

            return new MoveCommand($this->directionFactory->fromSlug($args[0]));
        }

        throw new InvalidArgumentException;
    }
}
