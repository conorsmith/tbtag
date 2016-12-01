<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Entity;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Holdable;
use ConorSmith\Tbtag\Inventory;

class PigeonAttemptsToLeaveWithSandwich extends GameEvent
{
    /** @var Inventory */
    private $inventory;

    /** @var Holdable */
    private $sandwich;

    public function __construct(Inventory $inventory, Holdable $sandwich)
    {
        $this->inventory = $inventory;
        $this->sandwich = $sandwich;
    }

    public function handle(Game $game)
    {
        $location = $game->findLocationOfAutomaton(Entity::PIGEON);
        $playerIsHere = $game->getCurrentLocation()->equals($location);

        if ($this->inventory->contains($this->sandwich)) {
            $this->inventory->remove($this->sandwich);
            $location->addToInventory($this->sandwich);

            if ($playerIsHere) {
                event(new SomethingHappens("You see a determined pigeon drop a sandwich while attempting to fly."));
            }

        } else if ($location->getInventory()->contains($this->sandwich)) {
            $location->removeFromInventory($this->sandwich);
            $this->inventory->add($this->sandwich);

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
