<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface Automaton
{
    public function __toString(): string;

    /**
     * @return void
     */
    public function takeAction();
}
