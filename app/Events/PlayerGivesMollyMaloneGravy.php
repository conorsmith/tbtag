<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Holdable;
use ConorSmith\Tbtag\Holder;
use ConorSmith\Tbtag\ItemIdentifier;

class PlayerGivesMollyMaloneGravy extends GameEvent
{
    /** @var Holder */
    private $holder;

    /** @var Holdable */
    private $holdable;

    public function __construct(Holder $holder, Holdable $holdable)
    {
        $this->holder = $holder;
        $this->holdable = $holdable;
    }

    public function handle(Game $game)
    {
        if ($this->holdable->is(ItemIdentifier::gravy())) {
            $game->moveFromPlayerToAHolder($this->holder, $this->holdable);
            event(new SomethingHappens(
                "She is pleased by your offer. Immediately she coats her brass cockles and mussels with the thick brown sauce. You have been granted free passage."
            ));
            return;
        }

        event(new SomethingHappens(sprintf("%s refuses to take %s.", strval($this->holder), strval($this->holdable))));
    }
}
