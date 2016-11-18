<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

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
