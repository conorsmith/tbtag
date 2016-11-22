<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\Payload;

class PlayerWins extends GameEvent implements Printable, EndsGame
{
    /** @var string */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function toPayload(): Payload
    {
        return new Payload($this->message);
    }
}
