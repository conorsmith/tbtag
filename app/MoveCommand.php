<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class MoveCommand extends Command
{
    const DESCRIPTION = "\033[1mnorth\033[0m|\033[1msouth\033[0m|\033[1meast\033[0m|\033[1mwest\033[0m Move in a direction.";

    private $direction;

    public function __construct(Direction $direction)
    {
        $this->direction = $direction;
    }

    public function getDirection(): Direction
    {
        return $this->direction;
    }
}
