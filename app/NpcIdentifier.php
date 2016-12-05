<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class NpcIdentifier implements AutomatonIdentifier
{
    use IdentifiedByName, EqualsIdentifierWithSameStringValue;

    public static function mollyMalone(): self
    {
        return new self("Molly Malone");
    }

    public static function pigeon(): self
    {
        return new self("Pigeon");
    }
}
