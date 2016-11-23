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
use ConorSmith\Tbtag\Events\PlayerWins;
use ConorSmith\Tbtag\Events\SomethingHappens;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Commands\HelpCommand;
use ConorSmith\Tbtag\HoldableFactory;
use ConorSmith\Tbtag\HoldableRegistry;
use ConorSmith\Tbtag\Inventory;
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
                HoldableFactory::sunglasses(),
                HoldableFactory::phone(),
                HoldableFactory::rifle()
            );
        });

        $this->app->singleton(Game::class, function ($app) {
            return new Game(
                new Map([
                    "1,0" => $startingLocation = new Location(
                        new LocationId("1,0"),
                        [
                            new Egress(new Direction("in"), new LocationId("wax:0,0")),
                            new Egress(new Direction("east"), new LocationId("1,1")),
                            new Egress(new Direction("south"), new LocationId("0,0")),
                        ],
                        "Foster Place",
                        "You are standing outside the Wax Museum. A number of wax figures are arranged outside the building, as if they are trying to escape. Wax Bono is giving you the stink eye.",
                        Inventory::unoccupied()
                    ),
                    "wax:0,0" => new Location(
                        new LocationId("wax:0,0"),
                        [
                            new Egress(new Direction("out"), new LocationId("1,0")),
                            new Egress(new Direction("east"), new LocationId("wax:0,1")),
                        ],
                        "Wax Museum",
                        "???",
                        Inventory::unoccupied()
                    ),
                    "wax:0,1" => new Location(
                        new LocationId("wax:0,1"),
                        [
                            new Egress(new Direction("west"), new LocationId("wax:0,0")),
                        ],
                        "Civil War Exhibit",
                        "???",
                        new Inventory([
                            HoldableFactory::rifle()
                        ]),
                        [],
                        [
                            [
                                'holdable' => HoldableFactory::rifle(),
                                'event'    => new SomethingHappens("You spot in Wax Dev's arms the rifle with which he shot and killed Michael Collins."),
                                'trigger'  => "static",
                            ]
                        ]
                    ),
                    "1,1" => new Location(
                        new LocationId("1,1"),
                        [
                            new Egress(new Direction("north"), new LocationId("1,2")),
                            new Egress(new Direction("west"), new LocationId("1,0")),
                            new Egress(new Direction("south"), new LocationId("0,1")),
                        ],
                        "College Green",
                        "You are amidst the wreckage of two Luas trams. It looks like there was some sort of head on collision.",
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
                        "Despite being moved back to her usual spot on Grafton Street, the statue of Molly Malone is back outside the church and also she's thirty feet tall.",
                        Inventory::unoccupied()
                    ),
                    "0,1" => new Location(
                        new LocationId("0,1"),
                        [
                            new Egress(new Direction("west"), new LocationId("0,0")),
                            new Egress(new Direction("north"), new LocationId("1,1")),
                        ],
                        "No 1 Grafton Street",
                        "The gates to the house of Trinity College's Provost are open. A low rumbling sound can be heard from inside the property. It's a little too close to brown noise for your liking.",
                        Inventory::unoccupied()
                    ),
                    "1,2" => new Location(
                        new LocationId("1,2"),
                        [
                            new Egress(new Direction("south"), new LocationId("1,1")),
                            new Egress(new Direction("north"), new LocationId("1,3")),
                        ],
                        "Public Toilet Pit",
                        "You stand above a pit where the College Street public toilets once stood.",
                        Inventory::unoccupied(),
                        [
                            new PlayerIsBlindedByTheSun
                        ]
                    ),
                    "1,3" => new Location(
                        new LocationId("1,3"),
                        [],
                        "KFC, Westmoreland Street",
                        "It's finger lickin' good.",
                        Inventory::unoccupied(),
                        [
                            new PlayerWins("You have reached your goal: KFC. Whatever else is going on in this crazy world you now have access to the best gravy known to humanity. Enjoy.")
                        ]
                    )
                ]),
                $startingLocation,
                new Inventory([HoldableFactory::phone()])
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
                "in",
                "out",
            ]);
        });

        $this->app->singleton(Listener::class, function ($app) {
            return new Listener($app[Game::class]);
        });
    }
}
