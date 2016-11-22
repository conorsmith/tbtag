<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Events\Printable;

interface Output
{
    public function outputEvent(Printable $event);
}
