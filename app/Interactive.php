<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;

interface Interactive
{
    public function intercept(Command $command): bool;
}
