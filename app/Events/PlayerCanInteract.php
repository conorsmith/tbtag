<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Location;
use ConorSmith\Tbtag\Ui\InteractionsPayload;
use ConorSmith\Tbtag\Ui\Payload;

class PlayerCanInteract extends GameEvent implements Printable
{
    private $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function toPayload(): Payload
    {
        return InteractionsPayload::fromLocation($this->location);
    }
}
