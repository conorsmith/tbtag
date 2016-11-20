<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

use ConorSmith\Tbtag\Egress;
use ConorSmith\Tbtag\Location;

class InteractionsPayload extends Payload
{
    public static function fromLocation(Location $location): self
    {
        return new static(
            collect($location->getEgresses())
                ->map(function (Egress $egress) {
                    return strval($egress->getDirection());
                })
                ->toArray()
        );
    }

    /** @var array */
    private $egressDirections;

    public function __construct(array $egressDirections)
    {
        $this->egressDirections = $egressDirections;
    }

    public function getEgressDirections(): array
    {
        return $this->egressDirections;
    }
}
