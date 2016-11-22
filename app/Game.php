<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\PlayerCanInteract;
use ConorSmith\Tbtag\Events\PlayerCannotCompleteMove;
use ConorSmith\Tbtag\Events\PlayerCannotGetHoldable;
use ConorSmith\Tbtag\Events\PlayerDoesNotHaveHoldable;
use ConorSmith\Tbtag\Events\PlayerDropsHoldable;
use ConorSmith\Tbtag\Events\PlayerEntersLocation;
use ConorSmith\Tbtag\Events\PlayerGetsHoldable;
use ConorSmith\Tbtag\Events\PlayerInspectsInventory;
use ConorSmith\Tbtag\Events\PlayerLooksAround;
use DomainException;

class Game
{
    /** @var Map */
    private $map;

    /** @var Location */
    private $currentLocation;

    /** @var Inventory */
    private $playerInventory;

    public function __construct(
        Map $map,
        Location $currentLocation,
        Inventory $playerInventory
    ) {
        $this->map = $map;
        $this->currentLocation = $currentLocation;
        $this->playerInventory = $playerInventory;
    }

    public function getCurrentLocation(): Location
    {
        return $this->currentLocation;
    }

    public function move(Direction $direction)
    {
        try {
            $newLocation = $this->map->findDestination($this->currentLocation, $direction);
            $this->map->addToHistory($this->currentLocation);
            $this->currentLocation = $newLocation;

        } catch (DomainException $e) {
            event(new PlayerCannotCompleteMove);
            return;
        }

        event(new PlayerEntersLocation($this->currentLocation));
    }

    public function isFirstVisitToTheLocation(): bool
    {
        return $this->map->isFirstVisitTo($this->currentLocation);
    }

    public function triggerLocationIngressEvents()
    {
        foreach ($this->currentLocation->getIngressEvents() as $event) {
            event($event);
        }
    }

    public function addToPlayerInventory(Holdable $holdable)
    {
        try {
            $this->currentLocation->removeFromInventory($holdable);

        } catch (DomainException $e) {
            event(new PlayerCannotGetHoldable);
            return;
        }

        foreach ($holdable->getPickUpEvents() as $event) {
            event($event);
        }

        $this->playerInventory->add($holdable);

        event(new PlayerGetsHoldable($holdable));
    }

    public function removeFromPlayerInventory(Holdable $holdable)
    {
        try {
            $this->playerInventory->remove($holdable);

        } catch (DomainException $e) {
            event(new PlayerDoesNotHaveHoldable);
            return;
        }

        foreach ($holdable->getDropEvents() as $event) {
            event($event);
        }

        $this->currentLocation->addToInventory($holdable);

        event(new PlayerDropsHoldable($holdable));
    }

    public function playerIsHolding(Holdable $holdable)
    {
        return $this->playerInventory->contains($holdable);
    }

    public function inspectPlayerInventory()
    {
        event(new PlayerInspectsInventory($this->playerInventory));
    }
}
