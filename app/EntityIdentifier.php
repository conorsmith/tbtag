<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface EntityIdentifier
{
    public function __toString(): string;

    public function equals(EntityIdentifier $identifier): bool;
}
