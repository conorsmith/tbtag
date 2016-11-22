<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\HoldableFactory;

class PlayerIsBlindedByTheSun extends GameEvent
{
    public function handle(Game $game)
    {
        if ($game->playerIsHolding(HoldableFactory::sunglasses())) {
            if ($game->isFirstVisitToTheLocation()) {
                event(new SomethingHappens(
                    "You are almost blinded by the brightness of the low winter sun."
                    . " Luckily you are wearing those sunglasses you found."
                ));
            }
        } else {
            event(new PlayerDies(
                "You are momentarily blinded by the brightness of the low winter sun. You trip and fall."
                . " Upon landing you hit your head on a piece of rubble and are killed instantly."
            ));
        }
    }
}
