<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\GameEvent;

class Egress
{
    /** @var Direction  */
    private $direction;

    /** @var LocationId */
    private $destination;

    /** @var Barrier */
    private $barrier;

    public function __construct(Direction $direction, LocationId $destination, Barrier $barrier = null)
    {
        $this->direction = $direction;
        $this->destination = $destination;
        $this->barrier = $barrier;
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
        return is_null($this->barrier) || $this->barrier->isNavigable();
    }

    public function getUnnavigableMessage(): string
    {
        if (is_null($this->barrier)) {
            return "";
        }

        return $this->barrier->getUnnavigableMessage();
    }

    public function triggerUsableEvents(GameEvent $event)
    {
        if (!is_null($this->barrier)) {
            $this->barrier->triggerUsableEvents($event);
        }
    }
}
