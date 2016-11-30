<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Entity;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\HoldableFactory;
use ConorSmith\Tbtag\Inventory;

class PigeonAttemptsToLeaveWithSandwich extends GameEvent
{
    /** @var Inventory */
    private $inventory;

    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function handle(Game $game)
    {
        $sandwich = HoldableFactory::sandwich();
        $location = $game->findLocationOfAutonomous(Entity::PIGEON);
        $playerIsHere = $game->getCurrentLocation()->equals($location);

        if ($this->inventory->contains($sandwich)) {
            $this->inventory->remove($sandwich);
            $location->addToInventory($sandwich);

            if ($playerIsHere) {
                event(new PlayerSeesPigeonDropSandwich);
            }

        } else if ($location->getInventory()->contains($sandwich)) {
            $location->removeFromInventory($sandwich);
            $this->inventory->add($sandwich);

            if ($playerIsHere) {
                event(new PlayerSeesPigeonPickUpSandwich);
            }

        } else {
            if ($playerIsHere) {
                event(new PlayerSeesPigeonWithoutASandwich);
            }
        }
    }
}
