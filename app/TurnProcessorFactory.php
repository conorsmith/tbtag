<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Ui\Interpreter;
use ConorSmith\Tbtag\Ui\Output;

class TurnProcessorFactory
{
    /** @var Interpreter */
    private $interpreter;

    /** @var Game */
    private $game;

    public function __construct(Interpreter $interpreter, Game $game)
    {
        $this->interpreter = $interpreter;
        $this->game = $game;
    }

    public function create(Output $output)
    {
        return new TurnProcessor($this->interpreter, $this->game, $output);
    }
}
