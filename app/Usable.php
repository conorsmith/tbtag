<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface Usable extends Entity
{
    public function triggerUse();
}
