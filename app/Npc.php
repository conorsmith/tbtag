<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;
use ConorSmith\Tbtag\Events\SomethingHappens;
use Illuminate\Support\Collection;

class Npc implements Automaton, Interactive
{
    /** @var NpcIdentifier */
    private $identifier;

    /** @var Inventory */
    private $inventory;

    /** @var array */
    private $actionEvents;

    /** @var array */
    private $giveEvents;

    /** @var Collection */
    private $interceptions;

    public function __construct(
        NpcIdentifier $identifier,
        Inventory $inventory = null,
        array $actionEvents = [],
        array $giveEvents = [],
        array $interceptionClasses = []
    ) {
        $this->identifier = $identifier;
        $this->inventory = $inventory ?? Inventory::unoccupied();
        $this->actionEvents = $actionEvents;
        $this->giveEvents = $giveEvents;
        $this->interceptions = collect($interceptionClasses)
            ->map(function (string $interceptionClass) {
                return new $interceptionClass($this);
            });
    }

    public function getId(): EntityIdentifier
    {
        return $this->identifier;
    }

    public function __toString(): string
    {
        return strval($this->identifier);
    }

    public function takeAction()
    {
        foreach ($this->actionEvents as $event) {
            event(new $event($this));
        }
    }

    public function triggerGiveEvents(Holdable $holdable)
    {
        if (count($this->giveEvents) === 0) {
            event(new SomethingHappens(sprintf("%s refuses to take %s.", $this->identifier, strval($holdable))));
        }

        foreach ($this->giveEvents as $event) {
            event(new $event($this, $holdable));
        }
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

    public function intercept(Command $command): bool
    {
        $commandWasIntercepted = false;

        foreach ($this->interceptions as $interception) {
            $commandWasIntercepted = $commandWasIntercepted || $interception->handle($command);
        }

        return $commandWasIntercepted;
    }
}
