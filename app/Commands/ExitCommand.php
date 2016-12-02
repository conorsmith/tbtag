<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Events\PlayerQuits;
use ConorSmith\Tbtag\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ExitCommand extends Command implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    const SLUG = "exit";
    const ALIASES = ["quit"];
    const ARGUMENTS = [];
    const DESCRIPTION = "Exit the game.";

    public function handle(Game $game)
    {
        event(new PlayerQuits);
    }
}
