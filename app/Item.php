<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\GameEvent;

class Item implements Holdable, Usable
{
    const EMP = "EMP";

    const EMP_USED = "EMP_USED";

    /** @var string */
    private $name;

    /** @var string */
    private $state;

    /** @var GameEvent */
    private $useEvent;

    public function __construct(string $name, GameEvent $useEvent = null)
    {
        $this->name = $name;
        $this->useEvent = $useEvent;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getPickUpEvents(): array
    {
        return [];
    }

    public function getDropEvents(): array
    {
        return [];
    }

    public function getSlug(): string
    {
        return strtolower($this->name);
    }

    public function triggerUse()
    {
        event($this->useEvent);
    }

    public function hasState(string $state): bool
    {
        return $this->state === $state;
    }

    public function setState(string $state)
    {
        $this->state = $state;
    }
}
