<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

interface Output
{
    public function payload(Payload $payload);
}
