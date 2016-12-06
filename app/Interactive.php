<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\Command;

interface Interactive extends Entity
{
    public function intercept(Command $command): bool;
}
