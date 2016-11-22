<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\PlayerCanInteract;
use ConorSmith\Tbtag\Events\PlayerCannotCompleteMove;
use ConorSmith\Tbtag\Events\PlayerEntersLocation;
use ConorSmith\Tbtag\Events\PlayerLooksAround;
use DomainException;

class Game
{
    /** @var Map */
    private $map;

    /** @var Location */
    private $currentLocation;

    public function __construct(Map $map, Location $currentLocation)
    {
        $this->map = $map;
        $this->currentLocation = $currentLocation;
    }

    public function getCurrentLocation(): Location
    {
        return $this->currentLocation;
    }

    public function move(Direction $direction)
    {
        try {
            $this->currentLocation = $this->map->findDestination($this->currentLocation, $direction);

        } catch (DomainException $e) {
            event(new PlayerCannotCompleteMove);
            return;
        }

        event(new PlayerEntersLocation($this->currentLocation));
    }

    public function isFirstVisitToTheLocation(): bool
    {
        return $this->map->isFirstVisitTo($this->currentLocation);
    }

    public function triggerLocationIngressEvents()
    {
        foreach ($this->currentLocation->getIngressEvents() as $event) {
            event($event);
        }
    }
}
