<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface Holdable
{
    public function getPickUpEvents(): array;
    public function getDropEvents(): array;
    public function getSlug(): string;
}
