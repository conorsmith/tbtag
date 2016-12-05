<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Automaton;
use ConorSmith\Tbtag\Events\SomethingHappens;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Holdable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GiveCommand extends Command implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    const SLUG = "give";
    const ALIASES = ["hand", "hand over", "provide", "present"];
    const ARGUMENTS = ["npc", "item"];
    const DESCRIPTION = "Give an item to an NPC.";

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
        if (!$game->getCurrentLocation()->houses($this->automaton->getIdentifier())) {
            event(new SomethingHappens(sprintf("%s is not here.", strval($this->automaton))));
            return;
        }

        if (!$game->playerIsHolding($this->holdable)) {
            $game->addToPlayerInventory($this->holdable);

            if (!$game->playerIsHolding($this->holdable)) {
                return;
            }
        }

        $this->automaton->triggerGiveEvents($this->holdable);
    }
}
