<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Events\PlayerRequestsHelp;
use ConorSmith\Tbtag\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HelpCommand extends Command implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    const SLUG = "help";
    const ALIASES = ["h", "?"];
    const ARGUMENTS = [];
    const DESCRIPTION = "Show these instructions.";

    private $commandClasses;

    public function __construct(array $commandClasses)
    {
        $this->commandClasses = $commandClasses;
    }

    public function handle(Game $game)
    {
        event(new PlayerRequestsHelp($this->commandClasses));
    }
}
