<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;
use ConorSmith\Tbtag\Events\PlayerDropsHoldable;
use ConorSmith\Tbtag\Events\PlayerEntersLocation;
use ConorSmith\Tbtag\Events\PlayerGetsHoldable;
use ConorSmith\Tbtag\Events\PlayerInspectsInventory;
use ConorSmith\Tbtag\Events\SomethingHappens;
use DomainException;
use OutOfBoundsException;

class Game
{
    /** @var Map */
    private $map;

    /** @var Registry */
    private $registry;

    /** @var Location */
    private $currentLocation;

    /** @var Location */
    private $locationOnPreviousTurn;

    /** @var Player */
    private $player;

    public function __construct(
        Map $map,
        Registry $registry,
        Location $currentLocation,
        Player $player
    ) {
        $this->map = $map;
        $this->registry = $registry;
        $this->currentLocation = $currentLocation;
        $this->player = $player;
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

        } catch (OutOfBoundsException $e) {
            event(new SomethingHappens("You cannot go that way."));
            return;

        } catch (DomainException $e) {
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
            event(new SomethingHappens(sprintf("You can't get %s.", $holdable)));
            return;
        }

        foreach ($holdable->getPickUpEvents() as $event) {
            event($event);
        }

        $this->player->addToInventory($holdable);

        event(new PlayerGetsHoldable($holdable));

        $this->currentLocation->triggerRemoveFromInventoryEvents();
    }

    public function removeFromPlayerInventory(Holdable $holdable)
    {
        try {
            $this->player->removeFromInventory($holdable);

        } catch (DomainException $e) {
            event(new SomethingHappens("You can't drop what you don't have."));
            return;
        }

        foreach ($holdable->getDropEvents() as $event) {
            event($event);
        }

        $this->currentLocation->addToInventory($holdable);

        event(new PlayerDropsHoldable($holdable));

        $this->currentLocation->triggerAddToInventoryEvents();
    }

    public function moveFromPlayerToAHolder(Holder $holder, Holdable $holdable)
    {
        try {
            $this->player->removeFromInventory($holdable);

        } catch (DomainException $e) {
            event(new SomethingHappens(sprintf("You don't have %s.", strval($holdable))));
            return;
        }

        $holder->addToInventory($holdable);

        event(new SomethingHappens(sprintf("You give %s to %s.", strval($holdable), strval($holder))));
    }

    public function playerIsHolding(Holdable $holdable)
    {
        return $this->player->isHolding($holdable);
    }

    public function inspectPlayerInventory()
    {
        event(new PlayerInspectsInventory($this->player));
    }

    public function processAutomatonActions()
    {
        $this->registry->allAutomatons()
            ->each(function (Automaton $automaton) {
                $automaton->takeAction();
            });
    }

    public function findLocation(EntityIdentifier $identifier): Location
    {
        if ($identifier instanceof HoldableIdentifier
            && $this->player->isHoldingByIdentifier($identifier)
        ) {
            return $this->currentLocation;
        }

        return $this->map->find($identifier);
    }

    public function processInteractiveInterceptions(Command $command): bool
    {
        return $this->currentLocation->processInteractiveInterceptions($command);
    }

    public function playerUses(Usable $usable)
    {
        if ($usable instanceof Holdable) {

            if (!$this->playerIsHolding($usable)) {
                $this->addToPlayerInventory($usable);
            }

            $usable->triggerUse();
            return;
        }

        throw new DomainException("Can't handle a non-holdable usuable.");
    }
}
