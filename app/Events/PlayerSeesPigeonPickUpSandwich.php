<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\Payload;

class PlayerSeesPigeonPickUpSandwich extends GameEvent implements Printable
{
    public function toPayload(): Payload
    {
        return new Payload("You notice a pigeon picking a sandwich up off the ground.");
    }
}
