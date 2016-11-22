<?php

namespace ConorSmith\Tbtag\Providers;

use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\Commands\DropCommand;
use ConorSmith\Tbtag\Commands\GetCommand;
use ConorSmith\Tbtag\Commands\InspectInventoryCommand;
use ConorSmith\Tbtag\Direction;
use ConorSmith\Tbtag\DirectionFactory;
use ConorSmith\Tbtag\Egress;
use ConorSmith\Tbtag\Commands\ExitCommand;
use ConorSmith\Tbtag\Events\PlayerIsBlindedByTheSun;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Commands\HelpCommand;
use ConorSmith\Tbtag\HoldableFactory;
use ConorSmith\Tbtag\HoldableRegistry;
use ConorSmith\Tbtag\Inventory;
use ConorSmith\Tbtag\Item;
use ConorSmith\Tbtag\Listener;
use ConorSmith\Tbtag\Location;
use ConorSmith\Tbtag\LocationId;
use ConorSmith\Tbtag\Commands\LookCommand;
use ConorSmith\Tbtag\Map;
use ConorSmith\Tbtag\Commands\MoveCommand;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(HoldableRegistry::class, function ($app) {
            return new HoldableRegistry(
                HoldableFactory::sunglasses()
            );
        });

        $this->app->singleton(Game::class, function ($app) {
            return new Game(
                new Map([
                    "1,0" => $startingLocation = new Location(
                        new LocationId("1,0"),
                        [
                            new Egress(new Direction("east"), new LocationId("1,1")),
                            new Egress(new Direction("south"), new LocationId("0,0")),
                        ],
                        "Foster Place",
                        "???",
                        Inventory::unoccupied()
                    ),
                    "1,1" => new Location(
                        new LocationId("1,1"),
                        [
                            new Egress(new Direction("north"), new LocationId("1,2")),
                            new Egress(new Direction("west"), new LocationId("1,0")),
                            new Egress(new Direction("south"), new LocationId("0,1")),
                        ],
                        "College Green",
                        "???",
                        new Inventory([
                            HoldableFactory::sunglasses(),
                        ])
                    ),
                    "0,0" => new Location(
                        new LocationId("0,0"),
                        [
                            new Egress(new Direction("east"), new LocationId("0,1")),
                            new Egress(new Direction("north"), new LocationId("1,0")),
                        ],
                        "St Andrew's Church",
                        "???",
                        Inventory::unoccupied()
                    ),
                    "0,1" => new Location(
                        new LocationId("0,1"),
                        [
                            new Egress(new Direction("west"), new LocationId("0,0")),
                            new Egress(new Direction("north"), new LocationId("1,1")),
                        ],
                        "No 1 Grafton Street",
                        "???",
                        Inventory::unoccupied()
                    ),
                    "1,2" => new Location(
                        new LocationId("1,2"),
                        [],
                        "Public Toilet Pit",
                        "You stand above a pit where the College Street public toilets once stood.",
                        Inventory::unoccupied(),
                        [
                            new PlayerIsBlindedByTheSun
                        ]
                    ),
                ]),
                $startingLocation,
                Inventory::unoccupied()
            );
        });

        $this->app->bind(CommandRepository::class, function ($app) {
            return new CommandRepository([
                HelpCommand::class,
                LookCommand::class,
                InspectInventoryCommand::class,
                MoveCommand::class,
                GetCommand::class,
                DropCommand::class,
                ExitCommand::class,
            ]);
        });

        $this->app->bind(DirectionFactory::class, function ($app) {
            return new DirectionFactory([
                "north",
                "south",
                "east",
                "west",
            ]);
        });

        $this->app->singleton(Listener::class, function ($app) {
            return new Listener($app[Game::class]);
        });
    }
}
