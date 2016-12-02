<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;
use ConorSmith\Tbtag\Events\SomethingHappens;

class Entity implements Automaton, Interactive
{
    /** @var string */
    private $name;

    /** @var Inventory */
    private $inventory;

    /** @var array */
    private $actionEvents;

    /** @var array */
    private $giveEvents;

    /** @var array */
    private $interceptions;

    public function __construct(
        string $name,
        Inventory $inventory = null,
        array $actionEvents = [],
        array $giveEvents = [],
        array $interceptions = []
    ) {
        $this->name = $name;
        $this->inventory = $inventory ?? Inventory::unoccupied();
        $this->actionEvents = $actionEvents;
        $this->giveEvents = $giveEvents;
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

    public function triggerGiveEvents(Holdable $holdable)
    {
        if (count($this->giveEvents) === 0) {
            event(new SomethingHappens(sprintf("%s refuses to take %s.", $this->name, strval($holdable))));
        }

        foreach ($this->giveEvents as $event) {
            event(new $event($this, $holdable));
        }
    }
    public function addToInventory(Holdable $holdable)
    {
        $this->inventory->add($holdable);
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
