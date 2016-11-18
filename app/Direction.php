<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Direction
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
