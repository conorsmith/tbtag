<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\GameEvent;
use ConorSmith\Tbtag\Events\SomethingHappens;

class BarrierEventConfig
{
    private $trigger;

    private $event;

    private $message;

    public function __construct(string $trigger, string $event, string $message = null)
    {
        $this->trigger = $trigger;
        $this->event = $event;
        $this->message = $message;
    }

    public function trigger(GameEvent $trigger, Barrier $barrier)
    {
        if (get_class($trigger) === $this->trigger) {
            event(new $this->event($barrier));
            if (!is_null($this->message)) {
                event(new SomethingHappens($this->message));
            }
        }
    }
}
