<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Automaton;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Holdable;

class PlayerGivesMollyMaloneGravy extends GameEvent
{
    /** @var Automaton */
    private $automaton;

    /** @var Holdable */
    private $holdable;

    public function __construct(Automaton $automaton, Holdable $holdable)
    {
        $this->automaton = $automaton;
        $this->holdable = $holdable;
    }

    public function handle(Game $game)
    {
        if ($this->holdable->is(Holdable::GRAVY)) {
            $game->moveFromPlayerToAutomatonInventory($this->automaton, $this->holdable);
            return;
        }

        event(new SomethingHappens(sprintf("%s refuses to take %s.", strval($this->automaton), strval($this->holdable))));
    }
}
