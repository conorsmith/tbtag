<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use InvalidArgumentException;

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

        return $this->dispatcher->__invoke(
            $this->commands->find($commandSlug),
            $this->findArguments($input, $commandSlug)
        );
    }

    private function findCommandSlug(Input $input)
    {
        foreach ($this->commands->getSlugs() as $commandSlug) {
            if (preg_match(sprintf("/^%s/", $commandSlug), strval($input)) === 1) {
                return $commandSlug;
            }
        }

        throw new InvalidArgumentException;
    }

    private function findArguments(Input $input, string $commandSlug)
    {
        return explode(" ", str_replace($commandSlug . " ", "", strval($input)));
    }
}
