<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Egress
{
    private $direction;
    private $destination;

    public function __construct(Direction $direction, LocationId $destination)
    {
        $this->direction = $direction;
        $this->destination = $destination;
    }

    public function getDirection(): Direction
    {
        return $this->direction;
    }

    public function getDestination(): LocationId
    {
        return $this->destination;
    }

    public function isNavigable(): bool
    {
        return true;
    }

    public function getUnnavigableMessage(): string
    {
        return "";
    }
}
