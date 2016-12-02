<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use ConorSmith\Tbtag\Ui\InteractionsPayload;
use ConorSmith\Tbtag\Ui\InventoryPayload;
use ConorSmith\Tbtag\Ui\LocationPayload;
use ConorSmith\Tbtag\Ui\Payload;
use ConorSmith\Tbtag\Ui\PlayerDeathPayload;
use ConorSmith\Tbtag\Ui\TabularPayload;
use Illuminate\Console\OutputStyle;

class Printer
{
    /** @var OutputStyle */
    private $output;

    /** @var TabularPrinter */
    private $tabularPrinter;

    public function __construct(OutputStyle $output, TabularPrinter $tabularPrinter)
    {
        $this->output = $output;
        $this->tabularPrinter = $tabularPrinter;
    }

    public function intro()
    {
        $this->output->writeln('   ______                     ____                  __');
        $this->output->writeln('  / ____/___ _____ ___  ___  / __ \__  _____  _____/ /_');
        $this->output->writeln(' / / __/ __ `/ __ `__ \/ _ \/ / / / / / / _ \/ ___/ __/');
        $this->output->writeln('/ /_/ / /_/ / / / / / /  __/ /_/ / /_/ /  __(__  ) /_');
        $this->output->writeln('\____/\__,_/_/ /_/ /_/\___/\___\_\__,_/\___/____/\__/');
        $this->output->writeln("");
        $this->output->writeln("\033[33mType 'help' for, well, help.\033[0m");
        $this->output->writeln("");
    }

    public function payload(Payload $payload)
    {
        if ($payload instanceof TabularPayload) {
            $this->tabularPrinter->__invoke($payload);
            return;
        }

        if ($payload instanceof LocationPayload) {
            $this->output->writeln("\033[1m" . $payload->getTitle() . "\033[0m");
            $this->output->writeln("\033[1m" . str_repeat("=", strlen($payload->getTitle())) . "\033[0m");
            $this->output->writeln("");
            return;
        }

        if ($payload instanceof InteractionsPayload) {
            if (count($payload->getItems()) > 0) {
                $this->output->writeln(sprintf("The items here are: %s", implode(", ", $payload->getItems())));
                $this->output->writeln("");
            }
            $this->output->writeln(sprintf("You can go: %s", implode(", ", $payload->getEgressDirections())));
            return;
        }

        if ($payload instanceof InventoryPayload) {
            $this->output->writeln("\033[1mYour Inventory\033[0m");
            $this->output->writeln("\033[1m--------------\033[0m");
            $this->output->writeln("");

            foreach ($payload->getContents() as $item) {
                $this->output->writeln(sprintf("* %s", $item));
            }

            if (count($payload->getContents()) === 0) {
                $this->output->writeln("Your inventory is empty");
            }

            $this->output->writeln("");

            return;
        }

        if ($payload instanceof PlayerDeathPayload) {
            $this->output->writeln(strval($payload));
            $this->output->writeln("");
            $this->output->writeln("\033[1mCare to try again?\033[0m\n");
            return;
        }

        $this->output->writeln(strval($payload));
        $this->output->writeln("");
    }
}
