<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Entity;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\HoldableFactory;
use ConorSmith\Tbtag\Inventory;
use ConorSmith\Tbtag\Ui\Payload;

class PigeonAttemptsToLeaveWithSandwich extends GameEvent implements Autonomous, Printable
{
    /** @var Inventory */
    private $inventory;

    /** @var bool */
    private $dropped = false;

    /** @var bool */
    private $givenUp = false;

    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function handle(Game $game)
    {
        $sandwich = HoldableFactory::sandwich();
        $location = $game->findLocationOf(Entity::PIGEON);

        if ($this->inventory->contains($sandwich)) {
            $this->dropped = true;
            $this->givenUp = false;
            $this->inventory->remove($sandwich);
            $location->addToInventory($sandwich);
        } else if ($location->getInventory()->contains($sandwich)) {
            $this->dropped = false;
            $this->givenUp = false;
            $location->removeFromInventory($sandwich);
            $this->inventory->add($sandwich);
        } else {
            $this->givenUp = true;
        }
    }

    public function doesPlayerShareTheLocation(Game $game): bool
    {
        $location = $game->findLocationOf(Entity::PIGEON);
        return $game->getCurrentLocation()->equals($location);
    }

    public function toPayload(): Payload
    {
        if ($this->givenUp) {
            return new Payload("You see a despondent pigeon.");
        }

        if ($this->dropped) {
            return new Payload("You see a determined pigeon drop a sandwich while attempting to fly.");
        } else {
            return new Payload("You notice a pigeon picking a sandwich up off the ground.");
        }
    }
}
