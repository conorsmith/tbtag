<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

use ConorSmith\Tbtag\Egress;
use ConorSmith\Tbtag\Location;

class LocationPayload extends Payload
{
    public static function fromLocation(Location $location): self
    {
        return new static(
            $location->getName(),
            $location->getDescription(),
            collect($location->getEgresses())
                ->map(function (Egress $egress) {
                    return strval($egress->getDirection());
                })
                ->toArray()
        );
    }

    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var array */
    private $egressDirections;

    public function __construct(string $title, string $description, array $egressDirections)
    {
        $this->title = $title;
        $this->description = $description;
        $this->egressDirections = $egressDirections;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getEgressDirections(): array
    {
        return $this->egressDirections;
    }
}
