<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\DirectionFactory;
use ConorSmith\Tbtag\HoldableRegistry;
use ConorSmith\Tbtag\Ui\MissingArgument;
use InvalidArgumentException;

class CommandFactory
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

    public function fromNameAndArguments(CommandName $commandName, array $args): Command
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
