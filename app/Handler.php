<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use DomainException;
use LogicException;

class Handler
{
    private $game;
    private $interpreter;

    public function __construct(Game $game, Interpreter $interpreter)
    {
        $this->game = $game;
        $this->interpreter = $interpreter;
    }

    public function __invoke(Command $command, Input $input): Payload
    {
        if ($command instanceof ExitCommand) {
            throw new ExitGame;
        }

        if ($command instanceof HelpCommand) {
            $output = [];

            foreach ($command->getCommandClasses() as $commandClass) {
                $output[] = [$commandClass::DESCRIPTION];
            }

            return new TabularPayload($output);
        }

        if ($command instanceof LookCommand) {
            return new Payload($this->game->getCurrentLocation()->describe());
        }

        if ($command instanceof MoveCommand) {
            try {
                $this->game->move($command->getDirection());
                return new Payload($this->game->getCurrentLocation()->describe());

            } catch (DomainException $e) {
                return new Payload("You cannot go that way.");
            }
        }

        throw new LogicException("No handler found for the command");
    }
}
