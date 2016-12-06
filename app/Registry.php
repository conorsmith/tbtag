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

    /** @var Collection */
    private $allEntities;

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

        $this->allEntities = $this->automatons
            ->merge($this->barriers)
            ->merge($this->holdables);
    }

    public function allAutomatons(): Collection
    {
        return $this->automatons;
    }

    public function find(EntityIdentifier $identifier): Entity
    {
        $slug = strtolower(strval($identifier));

        if ($identifier instanceof AutomatonIdentifier) {
            if (!$this->automatons->has($slug)) {
                throw new DomainException("Automaton doesn't exist.");
            }

            return $this->automatons[$slug];
        }

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

    public function findBySlug(string $class, string $slug): Entity
    {
        foreach ($this->allEntities as $key => $entity) {
            if ($entity instanceof $class
                && $key === $slug
            ) {
                return $entity;
            }
        }

        throw new LogicException("Unknown identifier");
    }

    public function hasEntityForSlug(string $class, string $slug): bool
    {
        try {
            $this->findBySlug($class, $slug);
            return true;

        } catch (LogicException $e) {
            return false;
        }
    }
}
