<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\HoldableRegistry;
use ConorSmith\Tbtag\Item;

class PlayerUsesEmp extends GameEvent
{
    public function handle(Game $game)
    {
        $emp = app(HoldableRegistry::class)->find(Item::EMP);

        if ($emp->hasState(Item::EMP_USED)) {
            event(new SomethingHappens("The EMP was already detonated. It cannot be used again."));
        } else {
            event(new EmpIsDetonated($emp));
        }
    }
}
