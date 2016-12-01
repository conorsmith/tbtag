<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use OutOfBoundsException;

class Map
{
    /** @var array */
    private $locations;

    /** @var array */
    private $locationHistory = [];

    public function __construct(array $locations)
    {
        $this->locations = $locations;
    }

    public function findDestination(Location $location, Direction $direction): Location
    {
        $destinationId = $location->findEgress($direction);

        return $this->locations[strval($destinationId)];
    }

    public function addToHistory(Location $location)
    {
        $this->locationHistory[] = $location->getId();
    }

    public function isFirstVisitTo(Location $location): bool
    {
        return !in_array($location->getId(), $this->locationHistory);
    }

    public function findLocationOfAutonomous(string $slug): Location
    {
        foreach ($this->locations as $location) {
            if ($location->houses($slug)) {
                return $location;
            }
        }

        throw new OutOfBoundsException("The given slug cannot be found in any location.");
    }

    public function findLocationOfHoldable(Holdable $holdable)
    {
        foreach ($this->locations as $location) {
            if ($location->hasInInventory($holdable)) {
                return $location;
            }
        }

        throw new OutOfBoundsException("The Holdable cannot be found in any location.");
    }
}
