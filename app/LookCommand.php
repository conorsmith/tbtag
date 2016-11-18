<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class LookCommand extends Command
{
    public function __invoke(Input $input): string
    {
        return $this->game->getCurrentLocation()->describe();
    }

    public function describe(): string
    {
        return "\033[1mlook\033[0m Look at your current surroundings.";
    }
}
