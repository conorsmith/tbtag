<?php

namespace ConorSmith\Tbtag\Providers;

use ConorSmith\Tbtag\Events\PlayerCanInteract;
use ConorSmith\Tbtag\Events\PlayerCannotCompleteMove;
use ConorSmith\Tbtag\Events\PlayerDies;
use ConorSmith\Tbtag\Events\PlayerEntersLocation;
use ConorSmith\Tbtag\Events\PlayerIsBlindedByTheSun;
use ConorSmith\Tbtag\Events\PlayerQuits;
use ConorSmith\Tbtag\Events\PlayerRequestsHelp;
use ConorSmith\Tbtag\Events\PlayerLooksAround;
use ConorSmith\Tbtag\Events\PlayerSeesWhereTheyAre;
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
        PlayerCanInteract::class        => [Listener::class],
        PlayerCannotCompleteMove::class => [Listener::class],
        PlayerDies::class               => [Listener::class],
        PlayerEntersLocation::class     => [Listener::class],
        PlayerIsBlindedByTheSun::class  => [Listener::class],
        PlayerRequestsHelp::class       => [Listener::class],
        PlayerSeesWhereTheyAre::class   => [Listener::class],
        PlayerLooksAround::class        => [Listener::class],
        PlayerQuits::class              => [Listener::class],
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
