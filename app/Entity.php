<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Entity implements Autonomous
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
