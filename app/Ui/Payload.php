<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

class Payload
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
