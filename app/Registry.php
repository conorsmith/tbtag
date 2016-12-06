<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use DomainException;
use Illuminate\Support\Collection;
use LogicException;

class Registry
{
    const ENTITY_IDENTIFIER_CLASSES_BY_ENTITY_CLASS = [
        Automaton::class   => AutomatonIdentifier::class,
        Barrier::class     => BarrierIdentifier::class,
        Holdable::class    => HoldableIdentifier::class,
        Interactive::class => InteractiveIdentifier::class,
        Usable::class      => UsableIdentifier::class,
    ];

    /** @var Collection */
    private $entities;

    /** @var Collection */
    private $entitiesByType;

    public function __construct(array $entities)
    {
        $this->entities = collect($entities);

        $this->entitiesByType = collect([
            Automaton::class,
            Barrier::class,
            Holdable::class,
            Interactive::class,
            Usable::class
        ])
            ->keyBy(function (string $class) {
                return self::ENTITY_IDENTIFIER_CLASSES_BY_ENTITY_CLASS[$class];
            })
            ->map(function (string $class) {
                return $this->entities
                    ->filter(function (Entity $entity) use ($class) {
                        return $entity instanceof $class;
                    })
                    ->keyBy(function (Entity $entity) {
                        return strtolower(strval($entity));
                    });
            });
    }

    public function allAutomatons(): Collection
    {
        return $this->entitiesByType[AutomatonIdentifier::class];
    }

    public function find(EntityIdentifier $identifier): Entity
    {
        $slug = strtolower(strval($identifier));

        foreach ($this->entitiesByType as $identifierClass => $entities) {
            if ($identifier instanceof $identifierClass) {
                if (!$entities->has($slug)) {
                    throw new DomainException("Entity doesn't exist.");
                }

                return $entities[$slug];
            }
        }

        throw new LogicException("Unknown identifier");
    }

    public function findBySlug(string $class, string $slug): Entity
    {
        foreach ($this->entities as $entity) {
            if ($entity instanceof $class
                && strtolower(strval($entity->getId())) === strtolower($slug)
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
