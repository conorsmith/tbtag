<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

abstract class Command
{
    protected $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    abstract public function __invoke(Input $input): string;

    abstract public function describe(): string;
}
