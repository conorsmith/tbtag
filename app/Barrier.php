<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\GameEvent;

class Barrier implements Entity
{
    /** @var string */
    private $identifier;

    /** @var string */
    private $unnavigableMessage;

    /** @var array */
    private $eventConfigs;

    /** @var bool */
    private $isNavigable;

    public function __construct(
        BarrierIdentifier $identifier,
        string $unnavigableMessage,
        array $eventConfigs
    ) {
        $this->identifier = $identifier;
        $this->unnavigableMessage = $unnavigableMessage;
        $this->eventConfigs = $eventConfigs;
        $this->isNavigable = false;
    }

    public function getId(): EntityIdentifier
    {
        return $this->identifier;
    }

    public function __toString(): string
    {
        return strval($this->identifier);
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
