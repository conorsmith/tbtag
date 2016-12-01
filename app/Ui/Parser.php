<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\Commands\CommandName;
use ConorSmith\Tbtag\Commands\MoveCommand;

class Parser
{
    /** @var CommandRepository */
    private $commands;

    public function __construct(CommandRepository $commands)
    {
        $this->commands = $commands;
    }

    public function parseCommand(Input $input): CommandName
    {
        $commandSlug = $this->parseCommandSlug($input);

        if (is_null($commandSlug)) {
            return CommandName::fromClassName(MoveCommand::class);
        }

        return CommandName::fromClassName(
            $this->commands->find($commandSlug)
        );
    }

    public function parseArguments(Input $input): array
    {
        $commandSlug = $this->parseCommandSlug($input);

        if (is_null($commandSlug)) {
            return explode(" ", strval($input));
        }

        $remainingInput = str_replace($commandSlug, "", strval($input));

        if ($remainingInput === "") {
            return [];
        }

        return explode(" ", substr($remainingInput, 1));
    }

    private function parseCommandSlug(Input $input)
    {
        foreach ($this->commands->getSlugs() as $commandSlug) {
            if (preg_match(
                    sprintf("/(^%s$|^%s )/", preg_quote($commandSlug), preg_quote($commandSlug)),
                    strval($input)
                ) === 1
            ) {
                return $commandSlug;
            }
        }

        return null;
    }
}
