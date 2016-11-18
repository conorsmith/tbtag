<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

class Handler
{
    public function __invoke(Command $command, Input $input): string
    {
        return $command($input);
    }
}
