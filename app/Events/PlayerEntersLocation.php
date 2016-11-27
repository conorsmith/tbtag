<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Location;

class PlayerEntersLocation extends GameEvent
{
    private $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function handle(Game $game)
    {
        event(new PlayerSeesWhereTheyAre($this->location));

        if ($game->isFirstVisitToTheLocation()) {
            event(new PlayerLooksAround($game->getCurrentLocation()));
        }

        $game->triggerLocationIngressEvents();
    }
}
