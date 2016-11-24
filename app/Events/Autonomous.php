<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Game;

interface Autonomous
{
    public function doesPlayerShareTheLocation(Game $game): bool;
}
