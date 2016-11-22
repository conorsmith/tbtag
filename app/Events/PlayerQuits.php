<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\Payload;

class PlayerQuits extends GameEvent implements Printable, EndsGame
{
    public function toPayload(): Payload
    {
        return new Payload("Goodbye!");
    }
}
