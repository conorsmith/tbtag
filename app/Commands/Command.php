<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Game;

abstract class Command
{
    abstract public function handle(Game $game);
}
