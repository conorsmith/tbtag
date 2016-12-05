<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class ItemIdentifier implements HoldableIdentifier
{
    public static function emp()
    {
        return new self("EMP");
    }

    public static function gravy()
    {
        return new self("Gravy");
    }

    public static function phone()
    {
        return new self("Phone");
    }

    public static function rifle()
    {
        return new self("Rifle");
    }

    public static function sandwich()
    {
        return new self("Sandwich");
    }

    public static function sunglasses()
    {
        return new self("Sunglasses");
    }

    /** @var string */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(EntityIdentifier $identifier): bool
    {
        return get_class($this) === get_class($identifier)
        && strval($this->value) === strval($identifier);
    }
}
