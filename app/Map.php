<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Map
{
    private $locations;

    public function __construct(array $locations)
    {
        $this->locations = $locations;
    }

    public function findDestination(Location $location, Direction $direction): Location
    {
        $destination = $location->findEgress($direction);

        return $this->locations[strval($destination)];
    }
}
