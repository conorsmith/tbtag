<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\Payload;

class PlayerCannotGetHoldable extends GameEvent implements Printable
{
    public function toPayload(): Payload
    {
        return new Payload("You can't get that.");
    }
}
