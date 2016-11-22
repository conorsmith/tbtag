<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class HoldableFactory
{
    public static function sunglasses(): Holdable
    {
        return new Item("Sunglasses");
    }
}
