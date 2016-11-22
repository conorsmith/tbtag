<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Item implements Holdable
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
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
}
