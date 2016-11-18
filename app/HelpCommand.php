<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class HelpCommand extends Command
{
    const DESCRIPTION = "\033[1mhelp\033[0m Show these instructions.";

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
