<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;
use ConorSmith\Tbtag\Commands\CommandName;
use ConorSmith\Tbtag\Commands\DropCommand;
use ConorSmith\Tbtag\Commands\GetCommand;
use ConorSmith\Tbtag\Commands\InspectInventoryCommand;
use ConorSmith\Tbtag\Commands\UseCommand;
use ConorSmith\Tbtag\Commands\ExitCommand;
use ConorSmith\Tbtag\Commands\HelpCommand;
use ConorSmith\Tbtag\Commands\LookCommand;
use ConorSmith\Tbtag\Commands\MoveCommand;
use ConorSmith\Tbtag\Ui\MissingArgument;
use InvalidArgumentException;

class Dispatcher
{
    /** @var CommandRepository */
    private $commands;

    /** @var DirectionFactory */
    private $directionFactory;

    /** @var HoldableRegistry */
    private $holdableRegistry;

    /** @var Game */
    private $game;

    public function __construct(
        CommandRepository $commands,
        DirectionFactory $directionFactory,
        HoldableRegistry $holdableRegistry,
        Game $game
    ) {
        $this->commands = $commands;
        $this->directionFactory = $directionFactory;
        $this->holdableRegistry = $holdableRegistry;
        $this->game = $game;
    }

    public function __invoke(CommandName $commandName, array $args): Command
    {
        $command = $this->createCommand($commandName, $args);

        if (!$this->game->processInteractiveInterceptions($command)) {
            dispatch($command);
        }

        return $command;
    }

    private function createCommand(CommandName $commandName, array $args): Command
    {
        if ($commandName->is(ExitCommand::class)) {
            return new ExitCommand;
        }

        if ($commandName->is(HelpCommand::class)) {
            return new HelpCommand($this->commands->getCommands());
        }

        if ($commandName->is(LookCommand::class)) {
            return new LookCommand;
        }

        if ($commandName->is(MoveCommand::class)) {
            if (count($args) !== 1) {
                throw new MissingArgument("Huh? Where do you want to go?");
            }

            return new MoveCommand($this->directionFactory->fromSlug($args[0]));
        }

        if ($commandName->is(InspectInventoryCommand::class)) {
            return new InspectInventoryCommand;
        }

        if ($commandName->is(GetCommand::class)) {
            if (count($args) !== 1) {
                throw new MissingArgument("Huh? What do you want to get?");
            }

            return new GetCommand($this->holdableRegistry->find($args[0]));
        }

        if ($commandName->is(DropCommand::class)) {
            if (count($args) !== 1) {
                throw new MissingArgument("Huh? What do you want to drop?");
            }

            return new DropCommand($this->holdableRegistry->find($args[0]));
        }

        if ($commandName->is(UseCommand::class)) {
            if (count($args) !== 1) {
                throw new MissingArgument("Buh? Use what?");
            }

            return new UseCommand($this->holdableRegistry->find($args[0]));
        }

        throw new InvalidArgumentException;
    }
}
