<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use ConorSmith\Tbtag\TurnProcessor;
use ConorSmith\Tbtag\TurnProcessorFactory;
use ConorSmith\Tbtag\Listener;
use ConorSmith\Tbtag\Ui\Output;
use ConorSmith\Tbtag\ExitGame;
use ConorSmith\Tbtag\Ui\Input;
use ConorSmith\Tbtag\Ui\Payload;
use Illuminate\Console\Command;

class PlayGame extends Command implements Output
{
    protected $signature = "play";

    protected $description = "Play the game";

    /** @var Listener */
    private $listener;

    /** @var PrinterBuilder */
    private $printerBuilder;

    /** @var Printer */
    private $printer;

    /** @var TurnProcessor */
    private $turnProcessor;

    public function __construct(
        Listener $listener,
        PrinterBuilder $printerBuilder,
        TurnProcessorFactory $turnProcessorFactory
    ) {
        parent::__construct();
        $this->listener = $listener;
        $this->printerBuilder = $printerBuilder;
        $this->turnProcessor = $turnProcessorFactory->create($this);
    }

    public function handle()
    {
        $this->printer = $this->printerBuilder
            ->withOutput($this->output)
            ->withTabularPrinter(new TabularPrinter($this))
            ->build();

        $this->listener->setOutput($this);

        $this->printer->intro();
        $this->processInput("look");
    }

    public function payload(Payload $payload)
    {
        $this->printer->payload($payload);
    }

    private function processInput(string $input)
    {
        try {
            $this->turnProcessor->__invoke(new Input($input));

        } catch (ExitGame $e) {
            return;
        }

        $this->processInput($this->ask("What do you want to do?"));
    }
}
