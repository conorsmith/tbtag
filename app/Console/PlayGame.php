<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use ConorSmith\Tbtag\Commands\InspectsArea;
use ConorSmith\Tbtag\Events\Printable;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Listener;
use ConorSmith\Tbtag\Ui\Output;
use ConorSmith\Tbtag\ExitGame;
use ConorSmith\Tbtag\Ui\Input;
use ConorSmith\Tbtag\Ui\InteractionsPayload;
use ConorSmith\Tbtag\Ui\Interpreter;
use ConorSmith\Tbtag\Ui\MissingArgument;
use ConorSmith\Tbtag\Ui\Payload;
use DomainException;
use Illuminate\Console\Command;
use InvalidArgumentException;

class PlayGame extends Command implements Output
{
    protected $signature = "play";

    protected $description = "Play the game";

    /** @var Interpreter */
    private $interpreter;

    /** @var Listener */
    private $listener;

    /** @var Game */
    private $game;

    /** @var PrinterBuilder */
    private $printerBuilder;

    /** @var Printer */
    private $printer;

    public function __construct(Interpreter $interpreter, Listener $listener, Game $game, PrinterBuilder $printerBuilder)
    {
        parent::__construct();
        $this->interpreter = $interpreter;
        $this->listener = $listener;
        $this->game = $game;
        $this->printerBuilder = $printerBuilder;
    }

    public function handle()
    {
        $this->printer = $this->printerBuilder
            ->withOutput($this->output)
            ->withTabularPrinter(new TabularPrinter($this))
            ->build();

        $this->listener->setOutput($this);

        $this->printer->intro();
        $this->handleInput("look");
    }

    public function payload(Payload $payload)
    {
        $this->printer->payload($payload);
    }

    private function handleInput(string $input)
    {
        $command = null;

        try {
            $command = $this->interpreter->__invoke(new Input($input));

        } catch (MissingArgument $e) {
            $this->printer->payload(new Payload($e->getMessage()));

        } catch (DomainException $e) {
            $this->printer->payload(new Payload($e->getMessage()));

        } catch (InvalidArgumentException $e) {
            $this->printer->payload(new Payload("I don't understand what you mean."));

        } catch (ExitGame $e) {
            return;
        }

        $this->game->processAutonomousActions();

        if (!is_null($command) && $command instanceof InspectsArea) {
            $this->printer->payload(InteractionsPayload::fromLocation($this->game->getCurrentLocation()));
        }

        $this->game->turnComplete();

        $this->handleInput($this->ask("What do you want to do?"));
    }
}
