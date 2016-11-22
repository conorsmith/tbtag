<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Holdable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DropCommand extends Command implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    const SLUG = "drop";
    const ALIASES = ["put down", "discard", "remove"];
    const ARGUMENTS = ["item"];
    const DESCRIPTION = "Drop an item from your inventory.";

    private $holdable;

    public function __construct(Holdable $holdable)
    {
        $this->holdable = $holdable;
    }

    public function handle(Game $game)
    {
        $game->removeFromPlayerInventory($this->holdable);
    }
}
