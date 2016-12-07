<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Holder;
use ConorSmith\Tbtag\ItemIdentifier;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Holdable;
use ConorSmith\Tbtag\NpcIdentifier;
use ConorSmith\Tbtag\Registry;

class PigeonAttemptsToLeaveWithSandwich extends GameEvent
{
    /** @var Holdable */
    private $pigeon;

    public function __construct(Holder $pigeon)
    {
        $this->pigeon = $pigeon;
    }

    public function handle(Game $game)
    {
        $location = $game->findLocationOfAutomaton(NpcIdentifier::pigeon());
        $playerIsHere = $game->getCurrentLocation()->equals($location);
        $sandwich = app(Registry::class)->find(ItemIdentifier::sandwich());

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
