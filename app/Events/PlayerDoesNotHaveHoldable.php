<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\Payload;

class PlayerDoesNotHaveHoldable extends GameEvent implements Printable
{
    public function toPayload(): Payload
    {
        return new Payload("You can't drop what you don't have.");
    }
}

