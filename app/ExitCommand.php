<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class ExitCommand extends Command
{
    const SLUG = "exit";
    const DESCRIPTION = "\033[1mexit\033[0m Exit the game.";
}
