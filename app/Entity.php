<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface Entity
{
    public function getId(): EntityIdentifier;
}
