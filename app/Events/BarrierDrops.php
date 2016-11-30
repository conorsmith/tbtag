<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Barrier;
use ConorSmith\Tbtag\Game;

class BarrierDrops extends GameEvent
{
    /** @var Barrier */
    private $barrier;

    public function __construct(Barrier $barrier)
    {
        $this->barrier = $barrier;
    }

    public function handle(Game $game)
    {
        $this->barrier->makeNavigable();
    }
}
