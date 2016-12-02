<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;

class Dispatcher
{
    /** @var Game */
    private $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function __invoke(Command $command)
    {
        if (!$this->game->processInteractiveInterceptions($command)) {
            $command->handle($this->game);
        }
    }
}
