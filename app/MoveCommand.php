<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class MoveCommand extends Command
{
    const SLUG = "move";
    const DESCRIPTION = "\033[1mmove\e[0m [\e[1mdirection\033[0m] Move in a direction.";

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
