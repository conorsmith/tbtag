<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\Commands\MoveCommand;

class Interpreter
{
    /** @var Dispatcher */
    private $dispatcher;

    /** @var CommandRepository */
    private $commands;

    public function __construct(Dispatcher $dispatcher, CommandRepository $commands)
    {
        $this->dispatcher = $dispatcher;
        $this->commands = $commands;
    }

    public function __invoke(Input $input)
    {
        $commandSlug = $this->findCommandSlug($input);

        if (is_null($commandSlug)) {
            $command = MoveCommand::class;
        } else {
            $command = $this->commands->find($commandSlug);
        }

        return $this->dispatcher->__invoke(
            $command,
            $this->findArguments($input, $commandSlug)
        );
    }

    private function findCommandSlug(Input $input)
    {
        foreach ($this->commands->getSlugs() as $commandSlug) {
            if (preg_match(
                    sprintf("/(^%s$|^%s )/", preg_quote($commandSlug), preg_quote($commandSlug)),
                    strval($input)
                ) === 1
            ) {
                return $commandSlug;
            }
        }

        return null;
    }

    private function findArguments(Input $input, string $commandSlug = null)
    {
        if (is_null($commandSlug)) {
            return explode(" ", strval($input));
        }

        $remainingInput = str_replace($commandSlug, "", strval($input));

        if ($remainingInput === "") {
            return [];
        }

        return explode(" ", substr($remainingInput, 1));
    }
}
