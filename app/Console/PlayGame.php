<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use ConorSmith\Tbtag\Events\PlayerEntersLocation;
use ConorSmith\Tbtag\Events\Printable;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Listener;
use ConorSmith\Tbtag\Output;
use ConorSmith\Tbtag\ExitGame;
use ConorSmith\Tbtag\Ui\Input;
use ConorSmith\Tbtag\Ui\InteractionsPayload;
use ConorSmith\Tbtag\Ui\Interpreter;
use ConorSmith\Tbtag\Commands\LookCommand;
use ConorSmith\Tbtag\Ui\LocationPayload;
use ConorSmith\Tbtag\Ui\MissingArgument;
use ConorSmith\Tbtag\Ui\Payload;
use ConorSmith\Tbtag\Ui\PlayerDeathPayload;
use ConorSmith\Tbtag\Ui\TabularPayload;
use Illuminate\Console\Command;
use InvalidArgumentException;

class PlayGame extends Command implements Output
{
    protected $signature = "play";

    protected $description = "Play the game";

    private $interpreter;
    private $listener;
    private $game;

    public function __construct(Interpreter $interpreter, Listener $listener, Game $game)
    {
        parent::__construct();
        $this->interpreter = $interpreter;
        $this->listener = $listener;
        $this->game = $game;
    }

    public function handle()
    {
        $this->listener->setOutput($this);
        $this->line("");
        //$this->handleInput(LookCommand::SLUG);
        event(new PlayerEntersLocation($this->game->getCurrentLocation()));
        $this->awaitInput();
    }

    private function awaitInput()
    {
        $this->handleInput($this->ask("What do you want to do?"));
    }

    private function handleInput(string $input)
    {
        try {
            $this->interpreter->__invoke(new Input($input));
            $this->awaitInput();

        } catch (MissingArgument $e) {
            $this->printPayload(new Payload($e->getMessage()));
            $this->awaitInput();

        } catch (InvalidArgumentException $e) {
            $this->printPayload(new Payload("I don't understand what you mean."));
            $this->awaitInput();

        } catch (ExitGame $e) {
            //
        }
    }

    public function outputEvent(Printable $event)
    {
        $this->printPayload($event->toPayload());
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
            return;
        }

        if ($payload instanceof InteractionsPayload) {
            $this->line("");
            $this->line(sprintf("You can go: %s", implode(", ", $payload->getEgressDirections())));
            return;
        }

        if ($payload instanceof PlayerDeathPayload) {
            $this->line("");
            $this->line(strval($payload));
            $this->line("");
            $this->printMessage("Care to try again?");
            return;
        }

        $this->line(strval($payload));
    }

    private function printMessage(string $message)
    {
        $this->line("\033[1m" . $message . "\033[0m\n");
    }
}
