<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

use ConorSmith\Tbtag\Inventory;

class InventoryPayload extends Payload
{
    public static function fromInventory(Inventory $inventory)
    {
        return new self(collect($inventory->getContents())
            ->map(function ($holdable) {
                return strval($holdable);
            })
            ->toArray());
    }

    /** @var array */
    private $contents;

    public function __construct(array $contents)
    {
        $this->contents = $contents;
    }

    public function getContents()
    {
        return $this->contents;
    }
}
