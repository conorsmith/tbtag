<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\DirectionFactory;
use ConorSmith\Tbtag\Commands\ExitCommand;
use ConorSmith\Tbtag\Commands\HelpCommand;
use ConorSmith\Tbtag\Commands\LookCommand;
use ConorSmith\Tbtag\Commands\MoveCommand;
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
            return dispatch(new ExitCommand);
        }

        if ($commandClass === HelpCommand::class) {
            return dispatch(new HelpCommand($this->commands->getCommands()));
        }

        if ($commandClass === LookCommand::class) {
            return dispatch(new LookCommand);
        }

        if ($commandClass === MoveCommand::class) {
            if (count($args) !== 1) {
                throw new MissingArgument("Huh? Where do you want to go?");
            }

            return dispatch(new MoveCommand($this->directionFactory->fromSlug($args[0])));
        }

        throw new InvalidArgumentException;
    }
}
