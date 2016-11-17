<?php
declare(strict_types=1);

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

class ExitCommand extends Command
{
    public function __invoke(Input $input): string
    {
        throw new ExitGame;
    }

    public function describe(): string
    {
        return "\033[1mexit\033[0m Exit the game.";
    }
}

class MoveCommand extends Command
{
    public function __invoke(Input $input): string
    {
        try {
            $this->game->move(new Direction(strval($input)));
            return $this->game->getCurrentLocation()->describe();

        } catch (DomainException $e) {
            return "You cannot go that way.";
        }
    }

    public function describe(): string
    {
        return "\033[1mnorth\033[0m|\033[1msouth\033[0m|\033[1meast\033[0m|\033[1mwest\033[0m Move in a direction.";
    }
}

class LookCommand extends Command
{
    public function __invoke(Input $input): string
    {
        return $this->game->getCurrentLocation()->describe();
    }

    public function describe(): string
    {
        return "\033[1mlook\033[0m Look at your current surroundings.";
    }
}

class HelpCommand extends Command
{
    private $commands;

    public function __construct(Game $game, CommandRepository $commands)
    {
        $this->commands = $commands;
        parent::__construct($game);
    }

    public function __invoke(Input $input): string
    {
        $output = "";

        foreach ($this->commands->getUnique() as $commandClass) {

            if ($commandClass === HelpCommand::class) {
                $command = new HelpCommand($this->game, $this->commands);
            } else {
                $command = new $commandClass($this->game);
            }

            $output .= $command->describe() . "\n";
        }

        return $output;
    }

    public function describe(): string
    {
        return "\033[1mhelp\033[0m Show these instructions.";
    }
}
