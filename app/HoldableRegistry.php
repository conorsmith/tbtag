<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use DomainException;

class HoldableRegistry
{
    private $holdables;

    public function __construct(Holdable ...$holdables)
    {
        $this->holdables = $holdables;
    }

    public function find(string $slug)
    {
        foreach ($this->holdables as $holdable)
        {
            if (strtolower(strval($holdable)) === strtolower($slug)) {
                return $holdable;
            }
        }

        throw new DomainException("That item is not here.");
    }
}
