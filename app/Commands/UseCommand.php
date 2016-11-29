<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Usable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UseCommand extends Command implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    const SLUG = "use";
    const ALIASES = [];
    const ARGUMENTS = ["item"];
    const DESCRIPTION = "Use an item.";

    private $usable;

    public function __construct(Usable $usable)
    {
        $this->usable = $usable;
    }

    public function handle(Game $game)
    {
        $game->playerUses($this->usable);
    }
}
