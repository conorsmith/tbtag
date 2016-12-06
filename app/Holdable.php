<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface Holdable extends Entity
{
    public function getPickUpEvents(): array;
    public function getDropEvents(): array;
    public function getSlug(): string;
    public function is(EntityIdentifier $identifier): bool;
}
