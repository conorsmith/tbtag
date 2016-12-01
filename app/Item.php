<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Item implements Holdable, Usable
{
    const EMP_USED = "EMP_USED";

    /** @var string */
    private $name;

    /** @var string */
    private $state;

    /** @var string */
    private $useEvent;

    public function __construct(string $name, string $useEvent = null)
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
        if (!is_null($this->useEvent)) {
            event(new $this->useEvent($this));
        }
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
