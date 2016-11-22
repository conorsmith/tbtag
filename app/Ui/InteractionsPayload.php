<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

use ConorSmith\Tbtag\Egress;
use ConorSmith\Tbtag\Holdable;
use ConorSmith\Tbtag\Location;

class InteractionsPayload extends Payload
{
    public static function fromLocation(Location $location): self
    {
        return new static(
            collect($location->getInventory()->getContents())
                ->map(function (Holdable $content) {
                    return strval($content);
                })
                ->toArray(),
            collect($location->getEgresses())
                ->map(function (Egress $egress) {
                    return strval($egress->getDirection());
                })
                ->toArray()
        );
    }

    /** @var array */
    private $items;

    /** @var array */
    private $egressDirections;

    public function __construct(array $items, array $egressDirections)
    {
        $this->items = $items;
        $this->egressDirections = $egressDirections;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getEgressDirections(): array
    {
        return $this->egressDirections;
    }
}
