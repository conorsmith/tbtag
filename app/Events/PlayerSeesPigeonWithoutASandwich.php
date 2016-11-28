<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\Payload;

class PlayerSeesPigeonWithoutASandwich extends GameEvent implements Printable
{
    public function toPayload(): Payload
    {
        return new Payload("You see a despondent pigeon.");
    }
}
