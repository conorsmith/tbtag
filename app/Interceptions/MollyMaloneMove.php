<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Interceptions;

use ConorSmith\Tbtag\Commands\Command;
use ConorSmith\Tbtag\Commands\MoveCommand;
use ConorSmith\Tbtag\Direction;
use ConorSmith\Tbtag\Events\PlayerDies;

class MollyMaloneMove implements Interception
{
    public function handle(Command $command): bool
    {
        if (!$command instanceof MoveCommand) {
            return false;
        }

        if (
            $command->getDirection()->equals(new Direction("south"))
            || $command->getDirection()->equals(new Direction("west"))
        ) {
            event(new PlayerDies(
                "As you attempt to move, Molly Malone roars. You fail to notice the lasers she fires at you from her eyes. You are now a small pile of dust."
            ));
            return true;
        }

        return false;
    }
}
