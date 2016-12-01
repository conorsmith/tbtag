<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface Automaton
{
    const MOLLY_MALONE = "Molly Malone";
    const PIGEON = "Pigeon";

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
