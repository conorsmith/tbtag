<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Commands\Command;

class ExitCommand extends Command
{
    const SLUG = "exit";
    const ALIASES = ["quit"];
    const ARGUMENTS = [];
    const DESCRIPTION = "Exit the game.";
}
