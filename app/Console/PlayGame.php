<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use ConorSmith\Tbtag\ExitGame;
use ConorSmith\Tbtag\Handler;
use ConorSmith\Tbtag\Input;
use ConorSmith\Tbtag\Interpreter;
use ConorSmith\Tbtag\Payload;
use ConorSmith\Tbtag\TabularPayload;
use Illuminate\Console\Command;
use InvalidArgumentException;

class PlayGame extends Command
{
    protected $signature = "play";

    protected $description = "Play the game";

    private $interpreter;
    private $handler;

    public function __construct(Interpreter $interpreter, Handler $handler)
    {
        parent::__construct();
        $this->interpreter = $interpreter;
        $this->handler = $handler;
    }

    public function handle()
    {
        $this->line("");
        $this->parseCommand("look");
    }

    private function getInput(): string
    {
        return $this->ask("What do you want to do?");
    }

    private function parseCommand(string $input)
    {
        $input = new Input($input);

        try {
            $command = $this->interpreter->__invoke($input);

        } catch (InvalidArgumentException $e) {
            $this->printMessage("I don't understand what you mean.");
            $this->parseCommand($this->getInput());
            return;
        }

        try {
            $this->printPayload($this->handler->__invoke($command, $input));
            $this->parseCommand($this->getInput());
            return;

        } catch (ExitGame $e) {
            $this->printMessage("Goodbye!");
            return;
        }
    }

    private function printPayload(Payload $payload)
    {
        if ($payload instanceof TabularPayload) {
            $this->table([], $payload->getData());
            return;
        }

        $this->printMessage(strval($payload));
    }

    private function printMessage(string $message)
    {
        $this->line("\033[1m" . $message . "\033[0m\n");
    }
}
