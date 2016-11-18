<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class ExitCommand extends Command
{
    public function __invoke(Input $input): string
    {
        throw new ExitGame;
    }

    public function describe(): string
    {
        return "\033[1mexit\033[0m Exit the game.";
    }
}
