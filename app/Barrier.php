<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\GameEvent;

class Barrier
{
    const BUS_GATE = "Bus Gate";

    /** @var string */
    private $name;

    /** @var string */
    private $unnavigableMessage;

    /** @var array */
    private $eventConfigs;

    /** @var bool */
    private $isNavigable;

    public function __construct(
        string $name,
        string $unnavigableMessage,
        array $eventConfigs
    ) {
        $this->name = $name;
        $this->unnavigableMessage = $unnavigableMessage;
        $this->eventConfigs = $eventConfigs;
        $this->isNavigable = false;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function isNavigable(): bool
    {
        return $this->isNavigable;
    }

    public function getUnnavigableMessage(): string
    {
        return $this->unnavigableMessage;
    }

    public function triggerUsableEvents(GameEvent $event)
    {
        foreach ($this->eventConfigs as $eventConfig) {
            $eventConfig->trigger($event, $this);
        }
    }

    public function makeNavigable()
    {
        $this->isNavigable = true;
    }
}
