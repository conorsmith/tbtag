<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use Illuminate\Database\Eloquent\Collection;

class Registry
{
    /** @var Collection */
    private $barriers;

    public function __construct(array $barriers)
    {
        $this->barriers = collect($barriers)
            ->keyBy(function (Barrier $barrier) {
                return strval($barrier);
            });
    }

    public function findBarrier(string $slug): Barrier
    {
        return $this->barriers[$slug];
    }
}
