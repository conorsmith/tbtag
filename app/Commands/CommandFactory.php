<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\DirectionFactory;
use ConorSmith\Tbtag\Holdable;
use ConorSmith\Tbtag\Interactive;
use ConorSmith\Tbtag\Registry;
use ConorSmith\Tbtag\Ui\MissingArgument;
use ConorSmith\Tbtag\Usable;
use InvalidArgumentException;

class CommandFactory
{
    /** @var CommandRepository */
    private $commands;

    /** @var DirectionFactory */
    private $directionFactory;

    /** @var Registry */
    private $registry;

    public function __construct(
        CommandRepository $commands,
        DirectionFactory $directionFactory,
        Registry $registry
    ) {
        $this->commands = $commands;
        $this->directionFactory = $directionFactory;
        $this->registry = $registry;
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

            return new GetCommand($this->registry->findBySlug(Holdable::class, $args[0]));
        }

        if ($commandName->is(DropCommand::class)) {
            if (count($args) !== 1) {
                throw new MissingArgument("Huh? What do you want to drop?");
            }

            return new DropCommand($this->registry->findBySlug(Holdable::class, $args[0]));
        }

        if ($commandName->is(UseCommand::class)) {
            if (count($args) !== 1) {
                throw new MissingArgument("Buh? Use what?");
            }

            return new UseCommand($this->registry->findBySlug(Usable::class, $args[0]));
        }

        if ($commandName->is(GiveCommand::class)) {
            if (count($args) < 2) {
                throw new MissingArgument("What? Give what? Come on...");
            }

            $automatonArgs = $args;
            $holdableArgs = $args;

            $automaton = null;
            $holdable = null;

            $potentialAutomatonSlug = [];

            while (is_null($automaton) && count($automatonArgs) > 0) {
                $potentialAutomatonSlug[] = array_shift($automatonArgs);

                if ($this->registry->hasEntityForSlug(Interactive::class, implode(" ", $potentialAutomatonSlug))) {
                    $automaton = $this->registry->findBySlug(Interactive::class, implode(" ", $potentialAutomatonSlug));
                }
            }

            if (!is_null($automaton)) {
                $potentialHoldableSlug = [];

                while (is_null($holdable) && count($automatonArgs) > 0) {
                    $potentialHoldableSlug[] = array_shift($automatonArgs);

                    if ($this->registry->hasEntityForSlug(Holdable::class, implode(" ", $potentialHoldableSlug))) {
                        $holdable = $this->registry->findBySlug(Holdable::class, implode(" ", $potentialHoldableSlug));
                    }
                }
            } else {
                $potentialHoldableSlug = [];

                while (is_null($holdable) && count($holdableArgs) > 0) {
                    $potentialHoldableSlug[] = array_shift($holdableArgs);

                    if ($this->registry->hasEntityForSlug(Holdable::class, implode(" ", $potentialHoldableSlug))) {
                        $holdable = $this->registry->findBySlug(Holdable::class, implode(" ", $potentialHoldableSlug));
                    }
                }

                if (!is_null($holdable)) {
                    $potentialConjunction = array_shift($holdableArgs);

                    if ($potentialConjunction === "to") {
                        $potentialAutomatonSlug = [];

                        while (is_null($automaton) && count($holdableArgs) > 0) {
                            $potentialAutomatonSlug[] = array_shift($holdableArgs);

                            if ($this->registry->hasEntityForSlug(Interactive::class, implode(" ", $potentialAutomatonSlug))) {
                                $automaton = $this->registry->findBySlug(Interactive::class, implode(" ", $potentialAutomatonSlug));
                            }
                        }
                    }
                }
            }

            if (is_null($automaton)) {
                throw new MissingArgument("What? Give to who?");
            }

            if (is_null($holdable)) {
                throw new MissingArgument("What? Give what?");
            }

            return new GiveCommand($automaton, $holdable);
        }

        throw new InvalidArgumentException;
    }
}
