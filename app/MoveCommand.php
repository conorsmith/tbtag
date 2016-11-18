<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use DomainException;

class MoveCommand extends Command
{
    public function __invoke(Input $input): string
    {
        try {
            $this->game->move(new Direction(strval($input)));
            return $this->game->getCurrentLocation()->describe();

        } catch (DomainException $e) {
            return "You cannot go that way.";
        }
    }

    public function describe(): string
    {
        return "\033[1mnorth\033[0m|\033[1msouth\033[0m|\033[1meast\033[0m|\033[1mwest\033[0m Move in a direction.";
    }
}
