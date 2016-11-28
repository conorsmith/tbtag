<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\PlayerCannotCompleteMove;
use ConorSmith\Tbtag\Events\PlayerCannotGetHoldable;
use ConorSmith\Tbtag\Events\PlayerDoesNotHaveHoldable;
use ConorSmith\Tbtag\Events\PlayerDropsHoldable;
use ConorSmith\Tbtag\Events\PlayerEntersLocation;
use ConorSmith\Tbtag\Events\PlayerGetsHoldable;
use ConorSmith\Tbtag\Events\PlayerInspectsInventory;
use DomainException;

class Game
{
    /** @var Map */
    private $map;

    /** @var AutonomousRegistry */
    private $autonomousRegistry;

    /** @var Location */
    private $currentLocation;

    /** @var Location */
    private $locationOnPreviousTurn;

    /** @var Inventory */
    private $playerInventory;

    public function __construct(
        Map $map,
        AutonomousRegistry $autonomousRegistry,
        Location $currentLocation,
        Inventory $playerInventory
    ) {
        $this->map = $map;
        $this->autonomousRegistry = $autonomousRegistry;
        $this->currentLocation = $currentLocation;
        $this->playerInventory = $playerInventory;
    }

    public function turnComplete()
    {
        $this->locationOnPreviousTurn = $this->currentLocation;
    }

    public function getCurrentLocation(): Location
    {
        return $this->currentLocation;
    }

    public function playerJustMovedHere(): bool
    {
        return !$this->currentLocation->equals($this->locationOnPreviousTurn);
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

        $this->currentLocation->triggerIngressInventoryEvents();
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

        $this->currentLocation->triggerRemoveFromInventoryEvents();
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

        $this->currentLocation->triggerAddToInventoryEvents();
    }

    public function playerIsHolding(Holdable $holdable)
    {
        return $this->playerInventory->contains($holdable);
    }

    public function inspectPlayerInventory()
    {
        event(new PlayerInspectsInventory($this->playerInventory));
    }

    public function processAutonomousActions()
    {
        $this->autonomousRegistry->processActions();
    }

    public function findLocationOf(string $slug): Location
    {
        return $this->map->findLocationOf($slug);
    }
}
