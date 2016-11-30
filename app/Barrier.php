<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\GameEvent;

class Barrier extends Egress
{
    /** @var string */
    private $unnavigableMessage;

    /** @var array */
    private $eventConfigs;

    /** @var bool */
    private $isNavigable;

    public function __construct(
        Direction $direction,
        LocationId $destination,
        string $unnavigableMessage,
        array $eventConfigs
    ) {
        $this->unnavigableMessage = $unnavigableMessage;
        $this->eventConfigs = $eventConfigs;
        $this->isNavigable = false;
        parent::__construct($direction, $destination);
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
