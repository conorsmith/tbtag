<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Game;

class PlayerIsBlindedByTheSun extends GameEvent
{
    public function handle(Game $game)
    {
        event(new PlayerDies(
            "You are momentarily blinded by the brightness of the low winter sun. You trip and fall."
            . " Upon landing you hit your head on a piece of rubble and are killed instantly."
        ));
    }
}
