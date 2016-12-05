<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface Holdable
{
    const EMP = "EMP";
    const GRAVY = "Gravy";
    const PHONE = "Phone";
    const RIFLE = "Rifle";
    const SANDWICH = "Sandwich";
    const SUNGLASSES = "Sunglasses";

    public function getPickUpEvents(): array;
    public function getDropEvents(): array;
    public function getSlug(): string;
    public function is(EntityIdentifier $identifier): bool;
}
