<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use ConorSmith\Tbtag\Ui\Controller;
use ConorSmith\Tbtag\ExitGame;
use ConorSmith\Tbtag\Handler;
use ConorSmith\Tbtag\Ui\Input;
use ConorSmith\Tbtag\Ui\InteractionsPayload;
use ConorSmith\Tbtag\Ui\Interpreter;
use ConorSmith\Tbtag\LookCommand;
use ConorSmith\Tbtag\Ui\LocationPayload;
use ConorSmith\Tbtag\Ui\Payload;
use ConorSmith\Tbtag\Ui\PlayerDeathPayload;
use ConorSmith\Tbtag\Ui\TabularPayload;
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
        $this->handleInput(LookCommand::SLUG);
    }

    private function handleInput(string $input)
    {
        try {
            collect(
                $this->controller->__invoke(new Input($input))
            )
                ->each(function ($payload) {
                    $this->line("");
                    $this->printPayload($payload);
                });

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

        if ($payload instanceof LocationPayload) {
            $this->line("\033[1m" . $payload->getTitle() . "\033[0m");
            $this->line("\033[1m" . str_repeat("=", strlen($payload->getTitle())) . "\033[0m");
            $this->line("");
            $this->line($payload->getDescription());
            return;
        }

        if ($payload instanceof InteractionsPayload) {
            $this->line(sprintf("You can go: %s", implode(", ", $payload->getEgressDirections())));
            return;
        }


        if ($payload instanceof PlayerDeathPayload) {
            $this->line(strval($payload));
            throw new ExitGame("\nCare to try again?");
        }

        $this->printMessage(strval($payload));
    }

    private function printMessage(string $message)
    {
        $this->line("\033[1m" . $message . "\033[0m\n");
    }
}
