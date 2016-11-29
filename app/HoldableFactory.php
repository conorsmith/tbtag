<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class HoldableFactory
{
    public static function sunglasses(): Holdable
    {
        return new Item("Sunglasses");
    }

    public static function phone(): Holdable
    {
        return new Item("Phone");
    }

    public static function rifle(): Holdable
    {
        return new Item("Rifle");
    }

    public static function sandwich(): Holdable
    {
        return new Item("Sandwich");
    }

    public static function emp(): Holdable
    {
        return new Item("EMP");
    }
}
