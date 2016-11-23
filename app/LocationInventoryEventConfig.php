<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\GameEvent;

class LocationInventoryEventConfig
{
    public static function ingress(Holdable $holdable, GameEvent $event)
    {
        return new self(["ingress"], $holdable, $event);
    }

    public static function add(Holdable $holdable, GameEvent $event)
    {
        return new self(["add"], $holdable, $event);
    }

    /** @var array */
    private $triggers;

    /** @var Holdable */
    private $holdable;

    /** @var GameEvent */
    private $event;

    public function __construct(array $triggers, Holdable $holdable, GameEvent $event)
    {
        $this->triggers = $triggers;
        $this->holdable = $holdable;
        $this->event = $event;
    }

    public function isIngress()
    {
        return in_array("ingress", $this->triggers);
    }

    public function isAdd()
    {
        return in_array("add", $this->triggers);
    }

    public function isRemove()
    {
        return in_array("remove", $this->triggers);
    }

    public function trigger(Inventory $inventory)
    {
        if ($inventory->contains($this->holdable)) {
            event($this->event);
        }
    }
}
