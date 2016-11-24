<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Entity implements Autonomous
{
    const MOLLY_MALONE = "Molly Malone";
    const PIGEON = "Pigeon";

    /** @var string */
    private $name;

    /** @var Inventory */
    private $inventory;

    /** @var array */
    private $actionEvents;

    public function __construct(string $name, Inventory $inventory = null, array $actionEvents = [])
    {
        $this->name = $name;
        $this->inventory = $inventory ?? Inventory::unoccupied();
        $this->actionEvents = $actionEvents;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function takeAction()
    {
        foreach ($this->actionEvents as $event) {
            event($event);
        }
    }
}
