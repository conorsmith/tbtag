<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use ConorSmith\Tbtag\ExitGame;
use ConorSmith\Tbtag\Input;
use ConorSmith\Tbtag\Interpreter;
use Illuminate\Console\Command;
use InvalidArgumentException;

class PlayGame extends Command
{
    protected $signature = "play";

    protected $description = "Play the game";

    private $interpreter;

    public function __construct(Interpreter $interpreter)
    {
        parent::__construct();
        $this->interpreter = $interpreter;
    }

    public function handle()
    {
        $this->line("");
        $this->parseCommand("look");
    }

    private function parseCommand(string $input)
    {
        $input = new Input($input);

        try {
            $command = $this->interpreter->__invoke($input);

        } catch (InvalidArgumentException $e) {
            $this->printMessage("I don't understand what you mean.");
            $this->parseCommand($this->ask("What do you want to do?"));
            return;
        }

        try {
            $this->printMessage($command($input));
            $this->parseCommand($this->ask("What do you want to do?"));
            return;

        } catch (ExitGame $e) {
            $this->printMessage("Goodbye!");
            return;
        }
    }

    private function printMessage(string $message)
    {
        $this->line("\033[1m" . $message . "\033[0m\n");
    }
}
