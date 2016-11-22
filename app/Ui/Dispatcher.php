<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\Commands\DropCommand;
use ConorSmith\Tbtag\Commands\GetCommand;
use ConorSmith\Tbtag\Commands\InspectInventoryCommand;
use ConorSmith\Tbtag\DirectionFactory;
use ConorSmith\Tbtag\Commands\ExitCommand;
use ConorSmith\Tbtag\Commands\HelpCommand;
use ConorSmith\Tbtag\Commands\LookCommand;
use ConorSmith\Tbtag\Commands\MoveCommand;
use ConorSmith\Tbtag\HoldableRegistry;
use ConorSmith\Tbtag\Item;
use InvalidArgumentException;

class Dispatcher
{
    /** @var CommandRepository */
    private $commands;

    /** @var DirectionFactory */
    private $directionFactory;

    /** @var HoldableRegistry */
    private $holdableRegistry;

    public function __construct(
        CommandRepository $commands,
        DirectionFactory $directionFactory,
        HoldableRegistry $holdableRegistry
    ) {
        $this->commands = $commands;
        $this->directionFactory = $directionFactory;
        $this->holdableRegistry = $holdableRegistry;
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

        if ($commandClass === InspectInventoryCommand::class) {
            return dispatch(new InspectInventoryCommand);
        }

        if ($commandClass === GetCommand::class) {
            if (count($args) !== 1) {
                throw new MissingArgument("Huh? What do you want to get?");
            }

            return dispatch(new GetCommand($this->holdableRegistry->find($args[0])));
        }

        if ($commandClass === DropCommand::class) {
            if (count($args) !== 1) {
                throw new MissingArgument("Huh? What do you want to drop?");
            }

            return dispatch(new DropCommand($this->holdableRegistry->find($args[0])));
        }

        throw new InvalidArgumentException;
    }
}
