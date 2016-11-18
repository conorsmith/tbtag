<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use InvalidArgumentException;

class Interpreter
{
    private $game;
    private $commands;

    public function __construct(Game $game, CommandRepository $commands)
    {
        $this->game = $game;
        $this->commands = $commands;
    }

    public function __invoke(Input $input)
    {
        $commandSlug = $this->findCommandSlug($input);
        $arguments = $this->findArguments($input, $commandSlug);

        return $this->createCommand($this->commands->find($commandSlug), $arguments);
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

    public function createCommand(string $commandClass, array $args)
    {
        if ($commandClass === ExitCommand::class)
        {
            return new ExitCommand;
        }

        if ($commandClass === HelpCommand::class) {
            return new HelpCommand($this->commands->getCommands());
        }

        if ($commandClass === LookCommand::class) {
            return new LookCommand;
        }

        if ($commandClass === MoveCommand::class && count($args) === 1) {
            return new MoveCommand(new Direction($args[0]));
        }

        throw new InvalidArgumentException;
    }
}
