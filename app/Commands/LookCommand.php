<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Events\PlayerCanInteract;
use ConorSmith\Tbtag\Events\PlayerLooksAround;
use ConorSmith\Tbtag\Events\PlayerSeesWhereTheyAre;
use ConorSmith\Tbtag\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LookCommand extends Command implements ShouldQueue, InspectsArea
{
    use InteractsWithQueue, Queueable;

    const SLUG = "look";
    const ALIASES = ["l", "look around", "see"];
    const ARGUMENTS = [];
    const DESCRIPTION = "Look at your current surroundings.";

    public function handle(Game $game)
    {
        event(new PlayerSeesWhereTheyAre($game->getCurrentLocation()));
        event(new PlayerLooksAround($game->getCurrentLocation()));
        $game->getCurrentLocation()->triggerLookInventoryEvents();
    }
}
