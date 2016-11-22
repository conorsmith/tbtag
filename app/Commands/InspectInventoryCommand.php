<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InspectInventoryCommand extends Command implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    const SLUG = "inventory";
    const ALIASES = ["i"];
    const ARGUMENTS = [];
    const DESCRIPTION = "Inspect your inventory.";

    public function handle(Game $game)
    {
        $game->inspectPlayerInventory();
    }
}
