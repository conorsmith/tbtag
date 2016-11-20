<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Game
{
    private $map;
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
        $this->currentLocation = $this->map->findDestination($this->currentLocation, $direction);

        foreach ($this->currentLocation->getIngressEvents() as $event) {
            $event();
        }
    }
}
