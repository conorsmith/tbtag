<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use DomainException;

class HoldableRegistry
{
    private $holdables;

    public function __construct(Holdable ...$holdables)
    {
        $this->holdables = collect($holdables)
            ->keyBy(function ($holdable) {
                return strtolower(strval($holdable));
            });
    }

    public function find(string $slug)
    {
        $slug = strtolower($slug);

        if ($this->holdables->has($slug)) {
            return $this->holdables[$slug];
        }

        throw new DomainException("That item is not here.");
    }
}
