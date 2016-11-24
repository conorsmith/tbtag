<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use OutOfBoundsException;

class AutonomousRegistry
{
    /** @var Autonomous[] */
    private $automatons;

    public function __construct(Autonomous ...$automatons)
    {
        $this->automatons = $automatons;
    }

    public function find(string $slug)
    {
        foreach ($this->automatons as $automaton) {
            if (strval($automaton) === $slug) {
                return $automaton;
            }
        }

        throw new OutOfBoundsException("Autonomous entry not found in Registry.");
    }

    public function processActions()
    {
        foreach ($this->automatons as $automaton) {
            $automaton->takeAction();
        }
    }
}
