<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\EndsGame;
use ConorSmith\Tbtag\Events\GameEvent;
use ConorSmith\Tbtag\Events\Printable;

class Listener
{
    /** @var Game */
    private $game;

    /** @var Output */
    private $output;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function setOutput(Output $output)
    {
        $this->output = $output;
    }

    public function handle(GameEvent $event)
    {
        if ($event instanceof Printable) {
            $this->output->outputEvent($event);
        }

        $event->handle($this->game);

        if ($event instanceof EndsGame) {
            throw new ExitGame;
        }
    }
}
