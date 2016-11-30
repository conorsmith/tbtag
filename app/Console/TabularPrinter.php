<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Console;

use ConorSmith\Tbtag\Ui\TabularPayload;
use Illuminate\Console\Command;

class TabularPrinter
{
    /** @var Command */
    private $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function __invoke(TabularPayload $payload)
    {
        $this->command->table([], $payload->getData());
    }
}
