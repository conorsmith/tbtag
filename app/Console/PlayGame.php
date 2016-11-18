<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use ConorSmith\Tbtag\Controller;
use ConorSmith\Tbtag\ExitGame;
use ConorSmith\Tbtag\Handler;
use ConorSmith\Tbtag\Input;
use ConorSmith\Tbtag\Interpreter;
use ConorSmith\Tbtag\LookCommand;
use ConorSmith\Tbtag\Payload;
use ConorSmith\Tbtag\TabularPayload;
use Illuminate\Console\Command;

class PlayGame extends Command
{
    protected $signature = "play";

    protected $description = "Play the game";

    private $interpreter;
    private $handler;
    private $controller;

    public function __construct(Interpreter $interpreter, Handler $handler, Controller $controller)
    {
        parent::__construct();
        $this->interpreter = $interpreter;
        $this->handler = $handler;
        $this->controller = $controller;
    }

    public function handle()
    {
        $this->line("");
        $this->handleInput(LookCommand::SLUG);
    }

    private function handleInput(string $input)
    {
        try {
            $this->printPayload($this->controller->__invoke(new Input($input)));

            $this->handleInput($this->ask("What do you want to do?"));

        } catch (ExitGame $e) {
            $this->printMessage($e->getMessage());
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
