<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use DomainException;
use Illuminate\Support\Collection;
use LogicException;

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

    public function find(EntityIdentifier $identifier)
    {
        $slug = strtolower(strval($identifier));

        if ($identifier instanceof BarrierIdentifier) {
            if (!$this->barriers->has($slug)) {
                throw new DomainException("Barrier doesn't exist.");
            }

            return $this->barriers[$slug];
        }

        if ($identifier instanceof HoldableIdentifier) {
            if (!$this->holdables->has($slug)) {
                throw new DomainException("Holdable doesn't exist.");
            }

            return $this->holdables[$slug];
        }

        throw new LogicException("Unknown identifier");
    }
}
