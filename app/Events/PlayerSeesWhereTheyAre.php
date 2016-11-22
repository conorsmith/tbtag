<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Location;
use ConorSmith\Tbtag\Ui\LocationPayload;
use ConorSmith\Tbtag\Ui\Payload;

class PlayerSeesWhereTheyAre extends GameEvent implements Printable
{
    private $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function toPayload(): Payload
    {
        return LocationPayload::fromLocation($this->location);
    }
}
