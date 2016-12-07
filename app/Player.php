<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Player implements Holder
{
    /** @var Inventory */
    private $inventory;

    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function getId(): EntityIdentifier
    {
        return new class implements EntityIdentifier
        {
            public function __toString(): string
            {
                return "Player";
            }

            public function equals(EntityIdentifier $other): bool
            {
                return strval($other) === "Player";
            }
        };
    }

    public function addToInventory(Holdable $holdable)
    {
        $this->inventory->add($holdable);
    }

    public function removeFromInventory(Holdable $holdable)
    {
        $this->inventory->remove($holdable);
    }

    public function isHolding(Holdable $holdable): bool
    {
        return $this->inventory->contains($holdable);
    }

    public function isHoldingByIdentifier(HoldableIdentifier $identifier): bool
    {
        return $this->inventory->containsByIdentifier($identifier);
    }

    public function getInventory(): Inventory
    {
        return $this->inventory;
    }
}
