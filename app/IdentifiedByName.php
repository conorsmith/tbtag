<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

trait IdentifiedByName
{
    /** @var string */
    protected $value;

    protected function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
