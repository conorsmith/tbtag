<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Automaton;
use ConorSmith\Tbtag\Npc;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Holdable;
use ConorSmith\Tbtag\Registry;

class PigeonAttemptsToLeaveWithSandwich extends GameEvent
{
    /** @var Automaton */
    private $pigeon;

    public function __construct(Automaton $pigeon)
    {
        $this->pigeon = $pigeon;
    }

    public function handle(Game $game)
    {
        $location = $game->findLocationOfAutomaton(Npc::PIGEON);
        $playerIsHere = $game->getCurrentLocation()->equals($location);
        $sandwich = app(Registry::class)->findHoldable(Holdable::SANDWICH);

        if ($this->pigeon->isHolding($sandwich)) {
            $this->pigeon->removeFromInventory($sandwich);
            $location->addToInventory($sandwich);

            if ($playerIsHere) {
                event(new SomethingHappens("You see a determined pigeon drop a sandwich while attempting to fly."));
            }

        } else if ($location->getInventory()->contains($sandwich)) {
            $location->removeFromInventory($sandwich);
            $this->pigeon->addToInventory($sandwich);

            if ($playerIsHere) {
                event(new SomethingHappens("You notice a pigeon picking a sandwich up off the ground."));
            }

        } else {
            if ($playerIsHere) {
                event(new SomethingHappens("You see a despondent pigeon."));
            }
        }
    }
}
