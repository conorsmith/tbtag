<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface Event
{
    public function __invoke();
}
