<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class PlayerSustainsHeadInjury implements Event
{
    public function __invoke()
    {
        throw new PlayerDied("You hit your head on a piece of rubble and are instantly killed.");
    }
}
