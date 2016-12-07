<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;
use ConorSmith\Tbtag\Events\GameEvent;
use ConorSmith\Tbtag\Events\SomethingHappens;
use DomainException;
use OutOfBoundsException;

class Location
{
    /** @var LocationId */
    private $id;

    /** @var array */
    private $egresses;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var Inventory */
    private $inventory;

    /** @var Manifest */
    private $manifest;

    /** @var array */
    private $ingressEvents;

    /** @var array */
    private $inventoryEvents;

    public function __construct(
        LocationId $id,
        array $egresses,
        string $name,
        string $description,
        Inventory $inventory = null,
        Manifest $manifest = null,
        array $ingressEvents = [],
        array $inventoryEvents = []
    ) {
        $this->id = $id;
        $this->egresses = $egresses;
        $this->name = $name;
        $this->description = $description;
        $this->inventory = $inventory ?? Inventory::unoccupied();
        $this->manifest = $manifest ?? Manifest::unoccupied();
        $this->ingressEvents = $ingressEvents;
        $this->inventoryEvents = $inventoryEvents;
    }

    public function describe(): string
    {
        return sprintf("You are at %s", $this->id);
    }

    public function getId(): LocationId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getEgresses(): array
    {
        return $this->egresses;
    }

    public function getInventory(): Inventory
    {
        return $this->inventory;
    }

    public function getIngressEvents(): array
    {
        return $this->ingressEvents;
    }

    public function triggerIngressInventoryEvents()
    {
        foreach ($this->inventoryEvents as $eventConfig) {
            if ($eventConfig->isIngress()) {
                $eventConfig->trigger($this->inventory);
            }
        }
    }

    public function triggerAddToInventoryEvents()
    {
        foreach ($this->inventoryEvents as $eventConfig) {
            if ($eventConfig->isAdd()) {
                $eventConfig->trigger($this->inventory);
            }
        }
    }

    public function triggerRemoveFromInventoryEvents()
    {
        foreach ($this->inventoryEvents as $eventConfig) {
            if ($eventConfig->isRemove()) {
                $eventConfig->trigger($this->inventory);
            }
        }
    }

    public function triggerLookInventoryEvents()
    {
        foreach ($this->inventoryEvents as $eventConfig) {
            if ($eventConfig->isLook()) {
                $eventConfig->trigger($this->inventory);
            }
        }
    }

    public function triggerUsableEvents(GameEvent $event)
    {
        foreach ($this->egresses as $egress) {
            $egress->triggerUsableEvents($event);
        }
    }

    public function findEgress(Direction $direction): LocationId
    {
        foreach ($this->egresses as $egress) {
            if ($egress->getDirection()->equals($direction)) {
                if (!$egress->isNavigable()) {
                    event(new SomethingHappens($egress->getUnnavigableMessage()));
                    throw new DomainException("Egress is not navigable");
                }

                return $egress->getDestination();
            }
        }

        throw new OutOfBoundsException("Egress not found");
    }

    public function removeFromInventory(Holdable $holdable)
    {
        $this->inventory->remove($holdable);
    }

    public function addToInventory(Holdable $holdable)
    {
        $this->inventory->add($holdable);
    }

    public function hasInInventory(Holdable $holdable): bool
    {
        return $this->inventory->contains($holdable);
    }

    public function houses(EntityIdentifier $identifier): bool
    {
        return $this->manifest->has($identifier);
    }

    public function equals(self $other): bool
    {
        return strval($this->id) === strval($other->id);
    }

    public function processInteractiveInterceptions(Command $command): bool
    {
        return $this->manifest->processInterceptions($command);
    }
}
