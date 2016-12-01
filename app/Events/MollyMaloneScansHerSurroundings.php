<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Entity;
use ConorSmith\Tbtag\Game;

class MollyMaloneScansHerSurroundings extends GameEvent
{
    public function handle(Game $game)
    {
        $location = $game->findLocationOfAutomaton(Entity::MOLLY_MALONE);

        if ($game->getCurrentLocation()->equals($location)) {
            if ($game->playerJustMovedHere()) {
                event(new SomethingHappens("The statue of Molly Malone turns her head to look right at you. She looks pissed."));
            } else {
                event(new PlayerDies(
                    "Molly Malone lets out a monstrous howl and her eyes begin to glow a deep crimson. You don't even live long enough to see the massive laser beam she fires at you, disintegrating your body instantly."
                ));
            }
        }
    }
}
