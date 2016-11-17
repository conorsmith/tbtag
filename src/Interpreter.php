<?php
declare(strict_types=1);

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
        $commandClass = $this->commands->find($input);

        if ($commandClass === HelpCommand::class) {
            return new HelpCommand($this->game, $this->commands);
        }

        return new $commandClass($this->game);
    }
}
