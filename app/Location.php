<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use DomainException;

class Location
{
    /** @var LocationId */
    private $id;

    /** @var array */
    private $egresses;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var array */
    private $ingressEvents;

    public function __construct(LocationId $id, array $egresses, string $name, string $description, array $ingressEvents = [])
    {
        $this->id = $id;
        $this->egresses = $egresses;
        $this->name = $name;
        $this->description = $description;
        $this->ingressEvents = $ingressEvents;
    }

    public function describe(): string
    {
        return sprintf("You are at %s", $this->id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getEgresses(): array
    {
        return $this->egresses;
    }

    public function getIngressEvents(): array
    {
        return $this->ingressEvents;
    }

    public function findEgress(Direction $direction): LocationId
    {
        foreach ($this->egresses as $egress) {
            if ($egress->getDirection()->equals($direction)) {
                return $egress->getDestination();
            }
        }

        throw new DomainException("Egress not found");
    }
}
