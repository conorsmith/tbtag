<?php

namespace ConorSmith\Tbtag\Providers;

use ConorSmith\Tbtag\AutonomousRegistry;
use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\Commands\DropCommand;
use ConorSmith\Tbtag\Commands\GetCommand;
use ConorSmith\Tbtag\Commands\InspectInventoryCommand;
use ConorSmith\Tbtag\Direction;
use ConorSmith\Tbtag\DirectionFactory;
use ConorSmith\Tbtag\Egress;
use ConorSmith\Tbtag\Commands\ExitCommand;
use ConorSmith\Tbtag\Entity;
use ConorSmith\Tbtag\Events\MollyMaloneScansHerSurroundings;
use ConorSmith\Tbtag\Events\PigeonAttemptsToLeaveWithSandwich;
use ConorSmith\Tbtag\Events\PlayerDies;
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
use ConorSmith\Tbtag\LocationInventoryEventConfig;
use ConorSmith\Tbtag\Manifest;
use ConorSmith\Tbtag\Map;
use ConorSmith\Tbtag\Commands\MoveCommand;
use ConorSmith\Tbtag\Interceptions\MollyMaloneMove;
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
                HoldableFactory::rifle(),
                HoldableFactory::sandwich()
            );
        });

        $this->app->singleton(AutonomousRegistry::class, function ($app) {
            return new AutonomousRegistry(
                new Entity(
                    Entity::MOLLY_MALONE,
                    Inventory::unoccupied(),
                    [
                        new MollyMaloneScansHerSurroundings
                    ],
                    [
                        new MollyMaloneMove
                    ]
                ),
                new Entity(
                    Entity::PIGEON,
                    $pigeonInventory = new Inventory([HoldableFactory::sandwich()]),
                    [
                        new PigeonAttemptsToLeaveWithSandwich($pigeonInventory)
                    ]
                )
            );
        });

        $this->app->singleton(Game::class, function ($app) {
            return new Game(
                new Map([
                    "1,3" => new Location(
                        new LocationId("1,3"),
                        [],
                        "KFC, Westmoreland Street",
                        "It's finger lickin' good.",
                        Inventory::unoccupied(),
                        Manifest::unoccupied(),
                        [
                            new PlayerWins("You have reached your goal: KFC. Whatever else is going on in this crazy world you now have access to the best gravy known to humanity. Enjoy.")
                        ]
                    ),
                    "1,2" => new Location(
                        new LocationId("1,2"),
                        [
                            new Egress(new Direction("north"), new LocationId("1,3")),
                            new Egress(new Direction("south"), new LocationId("1,1")),
                        ],
                        "Public Toilet Pit",
                        "You stand above a pit where the College Street public toilets once stood.",
                        Inventory::unoccupied(),
                        Manifest::unoccupied(),
                        [
                            new PlayerIsBlindedByTheSun
                        ]
                    ),
                    "0,1" => $startingLocation = new Location(
                        new LocationId("0,1"),
                        [
                            new Egress(new Direction("in"), new LocationId("wax:0,0")),
                            new Egress(new Direction("south"), new LocationId("0,0")),
                            new Egress(new Direction("east"), new LocationId("1,1")),
                        ],
                        "Foster Place",
                        "You are standing outside the Wax Museum. A number of wax figures are arranged outside the building, as if they are trying to escape. These are truly the most life-like wax figures you've ever seen and each one has a horrified expression."
                    ),
                    "wax:0,0" => new Location(
                        new LocationId("wax:0,0"),
                        [
                            new Egress(new Direction("out"), new LocationId("0,1")),
                            new Egress(new Direction("east"), new LocationId("wax:1,0")),
                        ],
                        "Wax Museum",
                        "Most of the space in the museum's main hall is taken up by a wax rendition of a gargantuan snake-like monster. The celebrity figures surrounding it are either dying or dead. A bisected Joe Dolan can be seen trying to keep his realistically recreated internal organs inside his wax body."
                    ),
                    "wax:1,0" => new Location(
                        new LocationId("wax:1,0"),
                        [
                            new Egress(new Direction("west"), new LocationId("wax:0,0")),
                        ],
                        "Civil War Exhibit",
                        "This special exhibit is untouched by the chaos from the front of the museum. It is very obvious that the historical figures here are just the wax figures of the actors who appeared in Neil Jordan's Michael Collins.",
                        new Inventory([
                            HoldableFactory::rifle()
                        ]),
                        Manifest::unoccupied(),
                        [],
                        [
                            LocationInventoryEventConfig::noticeable(
                                HoldableFactory::rifle(),
                                new SomethingHappens("You can see in Wax Alan Rickman's arms the actual rifle with which Dev shot and killed the Big Fella.")
                            )
                        ]
                    ),
                    "1,1" => new Location(
                        new LocationId("1,1"),
                        [
                            new Egress(new Direction("north"), new LocationId("1,2")),
                            new Egress(new Direction("south"), new LocationId("1,0")),
                            new Egress(new Direction("east"), new LocationId("2,1")),
                            new Egress(new Direction("west"), new LocationId("0,1")),
                        ],
                        "College Green",
                        "You are amidst the wreckage of two Luas trams. It looks like there was some sort of head on collision.",
                        new Inventory([
                            HoldableFactory::sunglasses(),
                        ])
                    ),
                    "2,1" => new Location(
                        new LocationId("2,1"),
                        [
                            new Egress(new Direction("south"), new LocationId("2,0")),
                            new Egress(new Direction("east"), new LocationId("3,1")),
                            new Egress(new Direction("west"), new LocationId("1,1")),
                        ],
                        "Front Square",
                        "???"
                    ),
                    "3,1" => new Location(
                        new LocationId("3,1"),
                        [
                            new Egress(new Direction("south"), new LocationId("3,0")),
                            new Egress(new Direction("east"), new LocationId("4,1")),
                            new Egress(new Direction("west"), new LocationId("2,1")),
                        ],
                        "New Square",
                        "???"
                    ),
                    "4,1" => new Location(
                        new LocationId("4,1"),
                        [
                            new Egress(new Direction("south"), new LocationId("4,0")),
                            new Egress(new Direction("west"), new LocationId("3,1")),
                        ],
                        "Rugby Pitch",
                        "???"
                    ),
                    "0,0" => new Location(
                        new LocationId("0,0"),
                        [
                            new Egress(new Direction("north"), new LocationId("0,1")),
                            new Egress(new Direction("south"), new LocationId("0,-1")),
                            new Egress(new Direction("east"), new LocationId("1,0")),
                        ],
                        "St Andrew's Church",
                        "Despite being moved back to her usual spot on Grafton Street after the Luas Cross City works were completed, the statue of Molly Malone is back outside the church.",
                        Inventory::unoccupied(),
                        new Manifest([
                            $app[AutonomousRegistry::class]->find(Entity::MOLLY_MALONE),
                        ])
                    ),
                    "1,0" => new Location(
                        new LocationId("1,0"),
                        [
                            new Egress(new Direction("north"), new LocationId("1,1")),
                            new Egress(new Direction("south"), new LocationId("1,-1")),
                            new Egress(new Direction("west"), new LocationId("0,0")),
                        ],
                        "No 1 Grafton Street",
                        "The gates to the house of Trinity College's Provost are open. A low rumbling sound can be heard from inside the property. It's a little too close to the brown note for your liking.",
                        Inventory::unoccupied(),
                        Manifest::unoccupied(),
                        [],
                        [
                            LocationInventoryEventConfig::add(
                                HoldableFactory::phone(),
                                new PlayerDies("As you put your phone down it starts to ring, but with the same sound coming from the Provost's House. You answer and the phone emits a high-powered, high-pitch noise that causes your head to explode instantly. You are no longer alive.")
                            )
                        ]
                    ),
                    "2,0" => new Location(
                        new LocationId("2,0"),
                        [
                            new Egress(new Direction("north"), new LocationId("2,1")),
                            new Egress(new Direction("east"), new LocationId("3,0")),
                        ],
                        "Fellows Square",
                        "???"
                    ),
                    "3,0" => new Location(
                        new LocationId("3,0"),
                        [
                            new Egress(new Direction("north"), new LocationId("3,1")),
                            new Egress(new Direction("west"), new LocationId("2,0")),
                        ],
                        "Pomedoro Sphere",
                        "???"
                    ),
                    "4,0" => new Location(
                        new LocationId("4,0"),
                        [
                            new Egress(new Direction("north"), new LocationId("4,1")),
                            new Egress(new Direction("west"), new LocationId("3,0")),
                        ],
                        "Cricket Pitch",
                        "???"
                    ),
                    "0,-1" => new Location(
                        new LocationId("0,-1"),
                        [
                            new Egress(new Direction("north"), new LocationId("0,0")),
                        ],
                        "Murphy's Ice Cream",
                        "???"
                    ),
                    "1,-1" => new Location(
                        new LocationId("1,-1"),
                        [
                            new Egress(new Direction("north"), new LocationId("1,0")),
                        ],
                        "Grafton Street",
                        "The way south is impassable. A mound of chicken nuggets two stories high has spilled out of McDonald's and is blocking the junction with Wicklow Street.",
                        Inventory::unoccupied(),
                        new Manifest([
                            $app[AutonomousRegistry::class]->find(Entity::PIGEON),
                        ])
                    ),
                ]),
                $app[AutonomousRegistry::class],
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
