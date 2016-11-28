<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use ConorSmith\Tbtag\Commands\InspectsArea;
use ConorSmith\Tbtag\Events\PlayerEntersLocation;
use ConorSmith\Tbtag\Events\Printable;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Listener;
use ConorSmith\Tbtag\Output;
use ConorSmith\Tbtag\ExitGame;
use ConorSmith\Tbtag\Ui\Input;
use ConorSmith\Tbtag\Ui\InteractionsPayload;
use ConorSmith\Tbtag\Ui\Interpreter;
use ConorSmith\Tbtag\Ui\InventoryPayload;
use ConorSmith\Tbtag\Ui\LocationPayload;
use ConorSmith\Tbtag\Ui\MissingArgument;
use ConorSmith\Tbtag\Ui\Payload;
use ConorSmith\Tbtag\Ui\PlayerDeathPayload;
use ConorSmith\Tbtag\Ui\TabularPayload;
use DomainException;
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
        $this->printIntro();
        $this->listener->setOutput($this);
        $this->line("");
        $this->handleInput("look");
    }

    private function awaitInput()
    {
        $this->handleInput($this->ask("What do you want to do?"));
    }

    private function handleInput(string $input)
    {
        $command = null;

        try {
            $command = $this->interpreter->__invoke(new Input($input));

        } catch (MissingArgument $e) {
            $this->printPayload(new Payload($e->getMessage()));

        } catch (DomainException $e) {
            $this->printPayload(new Payload($e->getMessage()));

        } catch (InvalidArgumentException $e) {
            $this->printPayload(new Payload("I don't understand what you mean."));

        } catch (ExitGame $e) {
            return;
        }

        $this->game->processAutonomousActions();

        if (!is_null($command) && $command instanceof InspectsArea) {
            $this->printPayload(InteractionsPayload::fromLocation($this->game->getCurrentLocation()));
        }

        $this->game->turnComplete();

        $this->awaitInput();
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
            if (count($payload->getItems()) > 0) {
                $this->line(sprintf("The items here are: %s", implode(", ", $payload->getItems())));
                $this->line("");
            }
            $this->line(sprintf("You can go: %s", implode(", ", $payload->getEgressDirections())));
            return;
        }

        if ($payload instanceof InventoryPayload) {
            $this->line("\033[1mYour Inventory\033[0m");
            $this->line("\033[1m--------------\033[0m");
            $this->line("");

            foreach ($payload->getContents() as $item) {
                $this->line(sprintf("* %s", $item));
            }

            if (count($payload->getContents()) === 0) {
                $this->line("Your inventory is empty");
            }

            return;
        }

        if ($payload instanceof PlayerDeathPayload) {
            $this->line(strval($payload));
            $this->line("");
            $this->printMessage("Care to try again?");
            return;
        }

        $this->line(strval($payload));
        $this->line("");
    }

    private function printMessage(string $message)
    {
        $this->line("\033[1m" . $message . "\033[0m\n");
    }

    private function printIntro()
    {
        $this->line('   ______                     ____                  __');
        $this->line('  / ____/___ _____ ___  ___  / __ \__  _____  _____/ /_');
        $this->line(' / / __/ __ `/ __ `__ \/ _ \/ / / / / / / _ \/ ___/ __/');
        $this->line('/ /_/ / /_/ / / / / / /  __/ /_/ / /_/ /  __(__  ) /_');
        $this->line('\____/\__,_/_/ /_/ /_/\___/\___\_\__,_/\___/____/\__/');
        $this->line("");
        $this->line("\033[33mType 'help' for, well, help.\033[0m");
    }
}
