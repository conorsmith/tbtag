<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Automaton;
use ConorSmith\Tbtag\ItemIdentifier;
use ConorSmith\Tbtag\Npc;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Holdable;
use ConorSmith\Tbtag\Registry;

class MollyMaloneScansHerSurroundings extends GameEvent
{
    /** @var Automaton */
    private $mollyMalone;

    public function __construct(Automaton $mollyMalone)
    {
        $this->mollyMalone = $mollyMalone;
    }

    public function handle(Game $game)
    {
        $location = $game->findLocationOfAutomaton(Npc::MOLLY_MALONE);
        $gravy = app(Registry::class)->findHoldable(ItemIdentifier::gravy());

        if ($game->getCurrentLocation()->equals($location)
            && !$this->mollyMalone->isHolding($gravy)
        ) {
            if ($game->playerJustMovedHere()) {
                event(new SomethingHappens("The statue of Molly Malone turns her head to look right at you. She looks pissed."));
            } else {
                event(new PlayerDies(
                    "Molly Malone lets out a monstrous howl and her eyes begin to glow a deep crimson. You don't even live long enough to see the massive laser beam she fires at you, disintegrating your body instantly."
                ));
            }
        }
    }
}
