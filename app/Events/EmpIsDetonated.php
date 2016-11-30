<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Item;
use ConorSmith\Tbtag\Ui\Payload;

class EmpIsDetonated extends GameEvent implements Printable
{
    private $emp;

    public function __construct(Item $emp)
    {
        $this->emp = $emp;
    }

    public function handle(Game $game)
    {
        $this->emp->setState(Item::EMP_USED);
        $empLocation = $game->findLocationOfHoldable($this->emp);
        $empLocation->triggerUsableEvents($this);
    }

    public function toPayload(): Payload
    {
        return new Payload("You detonate the EMP. A bright flash momentarily blinds you.");
    }
}
