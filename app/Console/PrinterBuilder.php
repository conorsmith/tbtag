<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use Illuminate\Console\OutputStyle;

class PrinterBuilder
{
    /** @var TabularPrinter */
    private $tabularPrinter;

    /** @var OutputStyle */
    private $output;

    public function withTabularPrinter(TabularPrinter $tabularPrinter): self
    {
        $this->tabularPrinter = $tabularPrinter;
        return $this;
    }

    public function withOutput(OutputStyle $output): self
    {
        $this->output = $output;
        return $this;
    }

    public function build(): Printer
    {
        return new Printer($this->output, $this->tabularPrinter);
    }
}
