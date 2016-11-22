<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Holdable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GetCommand extends Command implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    const SLUG = "get";
    const ALIASES = ["pick up", "fetch", "take"];
    const ARGUMENTS = ["item"];
    const DESCRIPTION = "Get an item.";

    private $holdable;

    public function __construct(Holdable $holdable)
    {
        $this->holdable = $holdable;
    }

    public function handle(Game $game)
    {
        $game->addToPlayerInventory($this->holdable);
    }
}
