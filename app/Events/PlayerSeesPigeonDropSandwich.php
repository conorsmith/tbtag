<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\Payload;

class PlayerSeesPigeonDropSandwich extends GameEvent implements Printable
{
    public function toPayload(): Payload
    {
        return new Payload("You see a determined pigeon drop a sandwich while attempting to fly.");
    }
}
