<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Item implements Holdable, Usable
{
    const EMP_USED = "EMP_USED";

    /** @var ItemIdentifier */
    private $identifier;

    /** @var string */
    private $state;

    /** @var string */
    private $useEvent;

    public function __construct(ItemIdentifier $identifier, string $useEvent = null)
    {
        $this->identifier = $identifier;
        $this->useEvent = $useEvent;
    }

    public function getId(): EntityIdentifier
    {
        return $this->identifier;
    }

    public function __toString()
    {
        return strval($this->identifier);
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
        return strtolower(strval($this->identifier));
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

    public function is(EntityIdentifier $identifier): bool
    {
        return $this->identifier->equals($identifier);
    }
}
