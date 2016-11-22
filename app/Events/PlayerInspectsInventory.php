<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Inventory;
use ConorSmith\Tbtag\Ui\InventoryPayload;
use ConorSmith\Tbtag\Ui\Payload;

class PlayerInspectsInventory extends GameEvent implements Printable
{
    private $inventory;

    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function toPayload(): Payload
    {
        return InventoryPayload::fromInventory($this->inventory);
    }
}
