<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;
use ConorSmith\Tbtag\Commands\CommandFactory;
use ConorSmith\Tbtag\Ui\Input;
use ConorSmith\Tbtag\Ui\Parser;

class Interpreter
{
    /** @var Parser */
    private $parser;

    /** @var CommandFactory */
    private $commandFactory;

    /** @var Dispatcher */
    private $dispatcher;

    public function __construct(Parser $parser, CommandFactory $commandFactory, Dispatcher $dispatcher)
    {
        $this->parser = $parser;
        $this->commandFactory = $commandFactory;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(Input $input): Command
    {
        $command = $this->commandFactory->fromNameAndArguments(
            $this->parser->parseCommand($input),
            $this->parser->parseArguments($input)
        );

        $this->dispatcher->__invoke($command);

        return $command;
    }
}
