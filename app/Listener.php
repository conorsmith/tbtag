<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Console\PlayGame;

class Listener
{
    /** @var PlayGame */
    private $cli;

    public function setCli(PlayGame $cli)
    {
        $this->cli = $cli;
    }

    public function handle($event)
    {
        $this->cli->handleEvent($event);
    }
}
