<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Holdable;
use ConorSmith\Tbtag\Item;
use ConorSmith\Tbtag\ItemIdentifier;
use ConorSmith\Tbtag\Registry;

class PlayerUsesEmp extends GameEvent
{
    public function handle(Game $game)
    {
        $emp = app(Registry::class)->find(ItemIdentifier::emp());

        if ($emp->hasState(Item::EMP_USED)) {
            event(new SomethingHappens("The EMP was already detonated. It cannot be used again."));
        } else {
            event(new EmpIsDetonated($emp));
        }
    }
}
