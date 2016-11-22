<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Holdable;
use ConorSmith\Tbtag\Ui\Payload;

class PlayerDropsHoldable extends GameEvent implements Printable
{
    private $holdable;

    public function __construct(Holdable $holdable)
    {
        $this->holdable = $holdable;
    }

    public function toPayload(): Payload
    {
        return new Payload(sprintf("You drop the %s", strval($this->holdable)));
    }
}
