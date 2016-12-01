<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use DomainException;
use Illuminate\Database\Eloquent\Collection;

class Registry
{
    /** @var Collection */
    private $barriers;

    /** @var Collection */
    private $holdables;

    public function __construct(array $barriers, array $holdables)
    {
        $this->barriers = collect($barriers)
            ->keyBy(function (Barrier $barrier) {
                return strtolower(strval($barrier));
            });

        $this->holdables = collect($holdables)
            ->keyBy(function (Holdable $holdable) {
                return strtolower(strval($holdable));
            });
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
}
