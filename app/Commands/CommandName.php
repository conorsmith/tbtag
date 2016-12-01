<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

final class CommandName
{
    public static function fromClassName(string $value): self
    {
        return new self($value);
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

    public function is(string $value): bool
    {
        return $this->value === $value;
    }
}
