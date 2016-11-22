<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Events\PlayerQuits;
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

    public function handle()
    {
        event(new PlayerQuits);
    }
}
