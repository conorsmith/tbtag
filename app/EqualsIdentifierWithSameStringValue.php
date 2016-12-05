<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

trait EqualsIdentifierWithSameStringValue
{
    public function equals(EntityIdentifier $identifier): bool
    {
        return get_class($this) === get_class($identifier)
            && strval($this) === strval($identifier);
    }
}
