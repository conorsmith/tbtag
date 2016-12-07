<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Player;
use ConorSmith\Tbtag\Ui\InventoryPayload;
use ConorSmith\Tbtag\Ui\Payload;

class PlayerInspectsInventory extends GameEvent implements Printable
{
    /** @var Player */
    private $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function toPayload(): Payload
    {
        return InventoryPayload::fromInventory($this->player->getInventory());
    }
}
