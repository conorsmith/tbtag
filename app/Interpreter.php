<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;
use ConorSmith\Tbtag\Ui\Input;
use ConorSmith\Tbtag\Ui\Parser;

class Interpreter
{
    /** @var Dispatcher */
    private $dispatcher;

    /** @var Parser */
    private $parser;

    public function __construct(Dispatcher $dispatcher, Parser $parser)
    {
        $this->dispatcher = $dispatcher;
        $this->parser = $parser;
    }

    public function __invoke(Input $input): Command
    {
        return $this->dispatcher->__invoke(
            $this->parser->parseCommand($input),
            $this->parser->parseArguments($input)
        );
    }
}
