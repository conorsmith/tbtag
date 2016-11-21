<?php

namespace ConorSmith\Tbtag\Providers;

use ConorSmith\Tbtag\Console\PlayGame;
use ConorSmith\Tbtag\Events\PlayerRequestsHelp;
use ConorSmith\Tbtag\Events\PlayerLooksAround;
use ConorSmith\Tbtag\Listener;
use Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PlayerRequestsHelp::class => [Listener::class],
        PlayerLooksAround::class  => [Listener::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
