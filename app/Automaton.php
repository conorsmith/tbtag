<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface Automaton extends Entity
{
    public function __toString(): string;

    /**
     * @return void
     */
    public function takeAction();

    /**
     * @return void
     */
    public function triggerGiveEvents(Holdable $holdable);
}
