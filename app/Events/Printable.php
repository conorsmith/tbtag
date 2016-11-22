<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Events;

use ConorSmith\Tbtag\Ui\Payload;

interface Printable
{
    public function toPayload(): Payload;
}
