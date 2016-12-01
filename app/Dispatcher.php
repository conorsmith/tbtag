<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;
use ConorSmith\Tbtag\Commands\CommandFactory;
use ConorSmith\Tbtag\Commands\CommandName;

class Dispatcher
{
    /** @var CommandFactory */
    private $commandFactory;

    /** @var Game */
    private $game;

    public function __construct(
        CommandFactory $commandFactory,
        Game $game
    ) {
        $this->commandFactory = $commandFactory;
        $this->game = $game;
    }

    public function __invoke(CommandName $commandName, array $args): Command
    {
        $command = $this->commandFactory->fromNameAndArguments($commandName, $args);

        if (!$this->game->processInteractiveInterceptions($command)) {
            dispatch($command);
        }

        return $command;
    }
}
