<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\TabularPayload;

class PlayerRequestsHelp
{
    private $commandClasses;

    public function __construct(array $commandClasses)
    {
        $this->commandClasses = $commandClasses;
    }

    public function toPayload()
    {
        $output = [];

        foreach ($this->commandClasses as $commandClass) {
            $input = $commandClass::SLUG;
            foreach ($commandClass::ARGUMENTS as $arg) {
                $input .= sprintf(" [%s]", $arg);
            }
            $output[] = [$input, $commandClass::DESCRIPTION];
        }

        return new TabularPayload($output);
    }
}
