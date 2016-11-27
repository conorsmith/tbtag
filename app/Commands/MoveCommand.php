<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Direction;
use ConorSmith\Tbtag\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MoveCommand extends Command implements ShouldQueue, InspectsArea
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
        $game->move($this->direction);
    }
}
