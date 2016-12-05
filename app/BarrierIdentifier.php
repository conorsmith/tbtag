<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class BarrierIdentifier implements EntityIdentifier
{
    use IdentifiedByName, EqualsIdentifierWithSameStringValue;

    public static function busGate(): self
    {
        return new self("Bus Gate");
    }

}
