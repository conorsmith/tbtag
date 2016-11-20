<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class MoveCommand extends Command
{
    const SLUG = "move";
    const ALIASES = ["go", "walk", "run"];
    const ARGUMENTS = ["direction"];
    const DESCRIPTION = "Move in a direction.";

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
