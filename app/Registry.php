<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use DomainException;
use Illuminate\Support\Collection;

class Registry
{
    /** @var Collection */
    private $automatons;

    /** @var Collection */
    private $barriers;

    /** @var Collection */
    private $holdables;

    public function __construct(array $automatons, array $barriers, array $holdables)
    {
        $this->automatons = collect($automatons)
            ->keyBy(function (Automaton $automaton) {
                return strtolower(strval($automaton));
            });

        $this->barriers = collect($barriers)
            ->keyBy(function (Barrier $barrier) {
                return strtolower(strval($barrier));
            });

        $this->holdables = collect($holdables)
            ->keyBy(function (Holdable $holdable) {
                return strtolower(strval($holdable));
            });
    }

    public function allAutomatons(): Collection
    {
        return $this->automatons;
    }

    public function findAutomaton(string $slug): Automaton
    {
        $slug = strtolower($slug);

        if (!$this->automatons->has($slug)) {
            throw new DomainException("Automaton doesn't exist.");
        }

        return $this->automatons[$slug];
    }

    public function hasAutomaton(string $slug): bool
    {
        return $this->automatons->has($slug);
    }

    public function findBarrier(string $slug): Barrier
    {
        $slug = strtolower($slug);

        if (!$this->barriers->has($slug)) {
            throw new DomainException("Barrier doesn't exist.");
        }

        return $this->barriers[$slug];
    }

    public function findHoldable(string $slug): Holdable
    {
        $slug = strtolower($slug);

        if (!$this->holdables->has($slug)) {
            throw new DomainException("Holdable doesn't exist.");
        }

        return $this->holdables[$slug];
    }

    public function hasHoldable(string $slug): bool
    {
        return $this->holdables->has($slug);
    }
}
