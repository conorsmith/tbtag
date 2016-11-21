<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Location;
use ConorSmith\Tbtag\Ui\LocationPayload;

class PlayerLooksAround
{
    private $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function toPayload()
    {
        return LocationPayload::fromLocation($this->location);
    }
}
