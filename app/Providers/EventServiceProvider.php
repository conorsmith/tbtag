<?php

namespace ConorSmith\Tbtag\Providers;

use ConorSmith\Tbtag\Events\BarrierDrops;
use ConorSmith\Tbtag\Events\EmpIsDetonated;
use ConorSmith\Tbtag\Events\MollyMaloneScansHerSurroundings;
use ConorSmith\Tbtag\Events\PigeonAttemptsToLeaveWithSandwich;
use ConorSmith\Tbtag\Events\PlayerDies;
use ConorSmith\Tbtag\Events\PlayerDropsHoldable;
use ConorSmith\Tbtag\Events\PlayerEntersLocation;
use ConorSmith\Tbtag\Events\PlayerGetsHoldable;
use ConorSmith\Tbtag\Events\PlayerInspectsInventory;
use ConorSmith\Tbtag\Events\PlayerQuits;
use ConorSmith\Tbtag\Events\PlayerRequestsHelp;
use ConorSmith\Tbtag\Events\PlayerLooksAround;
use ConorSmith\Tbtag\Events\PlayerSeesWhereTheyAre;
use ConorSmith\Tbtag\Events\PlayerUsesEmp;
use ConorSmith\Tbtag\Events\PlayerWins;
use ConorSmith\Tbtag\Events\SomethingHappens;
use ConorSmith\Tbtag\Listener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        BarrierDrops::class                      => [Listener::class],
        EmpIsDetonated::class                    => [Listener::class],
        MollyMaloneScansHerSurroundings::class   => [Listener::class],
        PigeonAttemptsToLeaveWithSandwich::class => [Listener::class],
        PlayerDies::class                        => [Listener::class],
        PlayerDropsHoldable::class               => [Listener::class],
        PlayerEntersLocation::class              => [Listener::class],
        PlayerGetsHoldable::class                => [Listener::class],
        PlayerInspectsInventory::class           => [Listener::class],
        PlayerRequestsHelp::class                => [Listener::class],
        PlayerSeesWhereTheyAre::class            => [Listener::class],
        PlayerLooksAround::class                 => [Listener::class],
        PlayerQuits::class                       => [Listener::class],
        PlayerUsesEmp::class                     => [Listener::class],
        PlayerWins::class                        => [Listener::class],
        SomethingHappens::class                  => [Listener::class],
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
