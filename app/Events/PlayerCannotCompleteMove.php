<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\Payload;

class PlayerCannotCompleteMove extends GameEvent implements Printable
{
    public function toPayload(): Payload
    {
        return new Payload("You cannot go that way.");
    }
}
