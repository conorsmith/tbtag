<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Game;

class PlayerFirstEntersLocation extends GameEvent
{
    /** @var string */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function handle(Game $game)
    {
        if ($game->isFirstVisitToTheLocation()) {
            event(new SomethingHappens($this->message));
        }
    }
}
