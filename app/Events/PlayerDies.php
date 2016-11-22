<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\Payload;
use ConorSmith\Tbtag\Ui\PlayerDeathPayload;

class PlayerDies extends GameEvent implements Printable, EndsGame
{
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function toPayload(): Payload
    {
        return new PlayerDeathPayload($this->message);
    }
}
