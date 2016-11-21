<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;
use ConorSmith\Tbtag\Commands\ExitCommand;
use ConorSmith\Tbtag\Commands\HelpCommand;
use ConorSmith\Tbtag\Commands\LookCommand;
use ConorSmith\Tbtag\Commands\MoveCommand;
use ConorSmith\Tbtag\Ui\Input;
use ConorSmith\Tbtag\Ui\InteractionsPayload;
use ConorSmith\Tbtag\Ui\Interpreter;
use ConorSmith\Tbtag\Ui\LocationPayload;
use ConorSmith\Tbtag\Ui\Payload;
use ConorSmith\Tbtag\Ui\PlayerDeathPayload;
use ConorSmith\Tbtag\Ui\TabularPayload;
use DomainException;
use InvalidArgumentException;
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

    public function __invoke(Command $command, Input $input): array
    {
        if ($command instanceof ExitCommand) {
            throw new ExitGame;
        }

        if ($command instanceof HelpCommand) {
            $output = [];

            foreach ($command->getCommandClasses() as $commandClass) {
                $input = $commandClass::SLUG;
                foreach ($commandClass::ARGUMENTS as $arg) {
                    $input .= sprintf(" [%s]", $arg);
                }
                $output[] = [$input, $commandClass::DESCRIPTION];
            }

            return [new TabularPayload($output)];
        }

        if ($command instanceof LookCommand) {
            return [
                LocationPayload::fromLocation($this->game->getCurrentLocation()),
                InteractionsPayload::fromLocation($this->game->getCurrentLocation()),
            ];
        }

        if ($command instanceof MoveCommand) {
            try {
                $this->game->move($command->getDirection());
                return [
                    LocationPayload::fromLocation($this->game->getCurrentLocation()),
                    InteractionsPayload::fromLocation($this->game->getCurrentLocation()),
                ];

            } catch (DomainException $e) {
                return [new Payload("You cannot go that way.")];

            } catch (PlayerDied $e) {
                return [
                    LocationPayload::fromLocation($this->game->getCurrentLocation()),
                    new PlayerDeathPayload($e->getMessage()),
                ];
            }
        }

        throw new LogicException("No handler found for the command");
    }
}
