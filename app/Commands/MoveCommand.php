<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Direction;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\PlayerDied;
use ConorSmith\Tbtag\Ui\InteractionsPayload;
use ConorSmith\Tbtag\Ui\LocationPayload;
use ConorSmith\Tbtag\Ui\Payload;
use ConorSmith\Tbtag\Ui\PlayerDeathPayload;
use DomainException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MoveCommand extends Command implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    const SLUG = "move";
    const ALIASES = ["go", "walk", "run"];
    const ARGUMENTS = ["direction"];
    const DESCRIPTION = "Move in a direction.";

    private $direction;

    public function __construct(Direction $direction)
    {
        $this->direction = $direction;
    }

    public function getDirection(): Direction
    {
        return $this->direction;
    }

    public function handle(Game $game)
    {
        try {
            $game->move($this->direction);
            return [
                LocationPayload::fromLocation($game->getCurrentLocation()),
                InteractionsPayload::fromLocation($game->getCurrentLocation()),
            ];

        } catch (DomainException $e) {
            return [new Payload("You cannot go that way.")];

        } catch (PlayerDied $e) {
            return [
                LocationPayload::fromLocation($game->getCurrentLocation()),
                new PlayerDeathPayload($e->getMessage()),
            ];
        }
    }
}
