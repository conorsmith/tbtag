<?php
declare(strict_types=1);

class Location
{
    private $id;
    private $egresses;

    public function __construct(LocationId $id, array $egresses)
    {
        $this->id = $id;
        $this->egresses = $egresses;
    }

    public function describe(): string
    {
        return sprintf("You are at %s", $this->id);
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
