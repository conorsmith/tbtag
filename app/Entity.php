<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;

class Entity implements Autonomous, Interactive
{
    const MOLLY_MALONE = "Molly Malone";
    const PIGEON = "Pigeon";

    /** @var string */
    private $name;

    /** @var Inventory */
    private $inventory;

    /** @var array */
    private $actionEvents;

    /** @var array */
    private $interceptions;

    public function __construct(
        string $name,
        Inventory $inventory = null,
        array $actionEvents = [],
        array $interceptions = []
    ) {
        $this->name = $name;
        $this->inventory = $inventory ?? Inventory::unoccupied();
        $this->actionEvents = $actionEvents;
        $this->interceptions = $interceptions;
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

    public function intercept(Command $command): bool
    {
        $commandWasIntercepted = false;

        foreach ($this->interceptions as $interception) {
            $commandWasIntercepted = $commandWasIntercepted || $interception->handle($command);
        }

        return $commandWasIntercepted;
    }
}
