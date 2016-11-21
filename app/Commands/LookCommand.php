<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Commands;

use ConorSmith\Tbtag\Commands\Command;

class LookCommand extends Command
{
    const SLUG = "look";
    const ALIASES = ["l", "look around", "see"];
    const ARGUMENTS = [];
    const DESCRIPTION = "Look at your current surroundings.";
}
