<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Interceptions;

use ConorSmith\Tbtag\Commands\Command;

interface Interception
{
    public function handle(Command $command): bool;
}
