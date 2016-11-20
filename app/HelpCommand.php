<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class HelpCommand extends Command
{
    const SLUG = "help";
    const ALIASES = ["h", "?"];
    const ARGUMENTS = [];
    const DESCRIPTION = "Show these instructions.";

    private $commandClasses;

    public function __construct(array $commandClasses)
    {
        $this->commandClasses = $commandClasses;
    }

    public function getCommandClasses(): array
    {
        return $this->commandClasses;
    }
}
