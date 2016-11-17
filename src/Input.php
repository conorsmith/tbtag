<?php
declare(strict_types=1);

class Input
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function is(string ...$others): bool
    {
        foreach ($others as $other) {
            if ($this->value === $other) {
                return true;
            }
        }

        return false;
    }

    public function __toString()
    {
        return $this->value;
    }
}
