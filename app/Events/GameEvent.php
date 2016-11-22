<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Game;

abstract class GameEvent
{
    public function handle(Game $game)
    {
        //
    }
}
