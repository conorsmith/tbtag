<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Barrier extends Egress
{
    private $unnavigableMessage;

    public function __construct(Direction $direction, LocationId $destination, string $unnavigableMessage)
    {
        $this->unnavigableMessage = $unnavigableMessage;
        parent::__construct($direction, $destination);
    }

    public function isNavigable(): bool
    {
        return false;
    }

    public function getUnnavigableMessage(): string
    {
        return $this->unnavigableMessage;
    }
}
