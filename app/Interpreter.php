<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

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
        return $this->createCommand($this->commands->find($input), $input);
    }

    public function createCommand(string $commandClass, Input $input)
    {
        if ($commandClass === ExitCommand::class)
        {
            return new ExitCommand;
        }

        if ($commandClass === HelpCommand::class) {
            return new HelpCommand($this->commands->getUnique());
        }

        if ($commandClass === LookCommand::class) {
            return new LookCommand;
        }

        if ($commandClass === MoveCommand::class) {
            return new MoveCommand(new Direction(strval($input)));
        }

        return new $commandClass($this->game);
    }
}
