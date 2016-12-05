<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class ItemIdentifier implements HoldableIdentifier
{
    use IdentifiedByName, EqualsIdentifierWithSameStringValue;

    public static function emp(): self
    {
        return new self("EMP");
    }

    public static function gravy(): self
    {
        return new self("Gravy");
    }

    public static function phone(): self
    {
        return new self("Phone");
    }

    public static function rifle(): self
    {
        return new self("Rifle");
    }

    public static function sandwich(): self
    {
        return new self("Sandwich");
    }

    public static function sunglasses(): self
    {
        return new self("Sunglasses");
    }
}
