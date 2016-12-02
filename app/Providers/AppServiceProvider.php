<?php

namespace ConorSmith\Tbtag\Providers;

use ConorSmith\Tbtag\Automaton;
use ConorSmith\Tbtag\Barrier;
use ConorSmith\Tbtag\BarrierEventConfig;
use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\Commands\DropCommand;
use ConorSmith\Tbtag\Commands\GetCommand;
use ConorSmith\Tbtag\Commands\GiveCommand;
use ConorSmith\Tbtag\Commands\InspectInventoryCommand;
use ConorSmith\Tbtag\Commands\UseCommand;
use ConorSmith\Tbtag\Direction;
use ConorSmith\Tbtag\DirectionFactory;
use ConorSmith\Tbtag\Egress;
use ConorSmith\Tbtag\Commands\ExitCommand;
use ConorSmith\Tbtag\Entity;
use ConorSmith\Tbtag\Events\BarrierDrops;
use ConorSmith\Tbtag\Events\EmpIsDetonated;
use ConorSmith\Tbtag\Events\MollyMaloneScansHerSurroundings;
use ConorSmith\Tbtag\Events\PigeonAttemptsToLeaveWithSandwich;
use ConorSmith\Tbtag\Events\PlayerDies;
use ConorSmith\Tbtag\Events\PlayerGivesMollyMaloneGravy;
use ConorSmith\Tbtag\Events\PlayerUsesEmp;
use ConorSmith\Tbtag\Events\PlayerWins;
use ConorSmith\Tbtag\Events\SomethingHappens;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\Commands\HelpCommand;
use ConorSmith\Tbtag\Holdable;
use ConorSmith\Tbtag\Inventory;
use ConorSmith\Tbtag\Item;
use ConorSmith\Tbtag\Listener;
use ConorSmith\Tbtag\Location;
use ConorSmith\Tbtag\LocationId;
use ConorSmith\Tbtag\Commands\LookCommand;
use ConorSmith\Tbtag\LocationInventoryEventConfig;
use ConorSmith\Tbtag\Manifest;
use ConorSmith\Tbtag\Map;
use ConorSmith\Tbtag\Commands\MoveCommand;
use ConorSmith\Tbtag\Interceptions\MollyMaloneMove;
use ConorSmith\Tbtag\Registry;
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
        $this->app->bind(CommandRepository::class, function ($app) {
            return new CommandRepository([
                HelpCommand::class,
                LookCommand::class,
                InspectInventoryCommand::class,
                MoveCommand::class,
                GetCommand::class,
                DropCommand::class,
                UseCommand::class,
                GiveCommand::class,
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

        $this->app->singleton(Registry::class, function ($app) {
            $sandwich = new Item(Holdable::SANDWICH);

            return new Registry(
                [
                    new Entity(
                        Automaton::MOLLY_MALONE,
                        Inventory::unoccupied(),
                        [
                            MollyMaloneScansHerSurroundings::class
                        ],
                        [
                            PlayerGivesMollyMaloneGravy::class
                        ],
                        [
                            MollyMaloneMove::class
                        ]
                    ),
                    new Entity(
                        Automaton::PIGEON,
                        new Inventory([$sandwich]),
                        [
                            PigeonAttemptsToLeaveWithSandwich::class
                        ]
                    )
                ],
                [
                    new Barrier(
                        Barrier::BUS_GATE,
                        "You are stopped by an invisible energy barrier. The College Green bus gate has advanced its technology.",
                        [
                            new BarrierEventConfig(
                                EmpIsDetonated::class,
                                BarrierDrops::class,
                                "The EMP disables the bus gate energy barrier."
                            ),
                        ]
                    )
                ],
                [
                    new Item(
                        Holdable::EMP,
                        PlayerUsesEmp::class
                    ),
                    new Item(Holdable::GRAVY),
                    new Item(Holdable::PHONE),
                    new Item(Holdable::RIFLE),
                    $sandwich,
                    new Item(Holdable::SUNGLASSES)
                ]
            );
        });

        $this->app->singleton(Game::class, function ($app) {
            return new Game(
                new Map([
                    "4,8" => new Location(
                        new LocationId("4,8"),
                        [
                            new Egress(new Direction("south"), new LocationId("4,7")),
                            new Egress(new Direction("in"), new LocationId("kfc:0,0")),
                        ],
                        "Westmoreland Street",
                        "You stand outside a KFC. Every other retail unit on the both sides of the street is now a Starbucks"
                    ),
                    "kfc:0,0" => new Location(
                        new LocationId("kfc:0,0"),
                        [
                            new Egress(new Direction("out"), new LocationId("4,8")),
                            new Egress(new Direction("west"), new LocationId("kfc:-1,0")),
                        ],
                        "KFC",
                        "The restaurant is abandoned. Several tables near the door have empty Starbucks cups on them. The order screen just says \"RUN\" over and over again."
                    ),
                    "kfc:-1,0" => new Location(
                        new LocationId("kfc:-1,0"),
                        [
                            new Egress(new Direction("east"), new LocationId("kfc:0,0")),
                        ],
                        "KFC Kitchen",
                        "The grill in the centre of the kitchen is malformed. It almost looks like it has parts of an espresso machine welded to it. Several unused chicken buckets have the Starbucks logo on them.",
                        new Inventory([
                            //$app[Registry::class]->findHoldable(Holdable::GRAVY),
                        ])
                    ),
                    "2,7" => $startingLocation = new Location(
                        new LocationId("2,7"),
                        [
                            new Egress(new Direction("east"), new LocationId("3,7")),
                        ],
                        "Central Bank",
                        "A massive Occupy Dame Street camp fills the plaza outside the Central Bank. It looks like the movement even took over the building itself. However, the camp appears to be deserted.",
                        new Inventory([
                            $app[Registry::class]->findHoldable(Holdable::EMP),
                        ])
                    ),
                    "3,7" => $startingLocation = new Location(
                        new LocationId("3,7"),
                        [
                            new Egress(new Direction("in"), new LocationId("wax:0,0")),
                            new Egress(new Direction("south"), new LocationId("3,6")),
                            new Egress(new Direction("east"), new LocationId("4,7"), $app[Registry::class]->findBarrier(Barrier::BUS_GATE)),
                            new Egress(new Direction("west"), new LocationId("2,7")),
                        ],
                        "Foster Place",
                        "You are standing outside the Wax Museum. A number of wax figures are arranged outside the building, as if they are trying to escape. These are truly the most life-like wax figures you've ever seen and each one has a horrified expression.",
                        new Inventory([
                            $app[Registry::class]->findHoldable(Holdable::GRAVY),
                        ])
                    ),
                    "wax:0,0" => new Location(
                        new LocationId("wax:0,0"),
                        [
                            new Egress(new Direction("out"), new LocationId("3,7")),
                            new Egress(new Direction("east"), new LocationId("wax:1,0")),
                            new Egress(new Direction("west"), new LocationId("wax:-1,0")),
                        ],
                        "Wax Museum",
                        "Most of the space in the museum's main hall is taken up by a wax rendition of a gargantuan snake-like monster. The celebrity figures surrounding it are either dying or dead. A bisected Joe Dolan can be seen trying to keep his realistically recreated internal organs inside his wax body.\n\nTo the east you see a door marked \"Special Exhibit\". To the west a door that says \"DANGER: Deep Excavations\"."
                    ),
                    "wax:-1,0" => new Location(
                        new LocationId("wax:-1,0"),
                        [],
                        "A Big Hole",
                        "You fall into a big hole.",
                        Inventory::unoccupied(),
                        Manifest::unoccupied(),
                        [
                            new PlayerDies(
                                "As you fall towards your messy death you have just enough time to wonder why such a deep hole would be dug indoors."
                            ),
                        ]
                    ),
                    "wax:1,0" => new Location(
                        new LocationId("wax:1,0"),
                        [
                            new Egress(new Direction("west"), new LocationId("wax:0,0")),
                        ],
                        "Civil War Exhibit",
                        "This special exhibit is untouched by the chaos from the front of the museum. It is very obvious that the historical figures here are just the wax figures of the actors who appeared in Neil Jordan's Michael Collins.",
                        new Inventory([
                            $app[Registry::class]->findHoldable(Holdable::RIFLE)
                        ]),
                        Manifest::unoccupied(),
                        [],
                        [
                            LocationInventoryEventConfig::noticeable(
                                $app[Registry::class]->findHoldable(Holdable::RIFLE),
                                new SomethingHappens("You can see in Wax Alan Rickman's arms the actual rifle with which Dev shot and killed the Big Fella.")
                            )
                        ]
                    ),
                    "4,7" => new Location(
                        new LocationId("4,7"),
                        [
                            new Egress(new Direction("north"), new LocationId("4,8")),
                            new Egress(new Direction("south"), new LocationId("4,6")),
                            /*new Egress(new Direction("east"), new LocationId("5,7")),*/
                            new Egress(new Direction("west"), new LocationId("3,7"), $app[Registry::class]->findBarrier(Barrier::BUS_GATE)),
                        ],
                        "College Green",
                        "You are amidst the wreckage of two Luas trams. It looks like there was some sort of head on collision.",
                        new Inventory([
                            $app[Registry::class]->findHoldable(Holdable::SUNGLASSES),
                        ])
                    ),
                    "5,7" => new Location(
                        new LocationId("5,7"),
                        [
                            new Egress(new Direction("south"), new LocationId("5,6")),
                            new Egress(new Direction("east"), new LocationId("6,7")),
                            new Egress(new Direction("west"), new LocationId("4,7")),
                        ],
                        "Front Square",
                        "???"
                    ),
                    "6,7" => new Location(
                        new LocationId("6,7"),
                        [
                            new Egress(new Direction("south"), new LocationId("6,6")),
                            new Egress(new Direction("east"), new LocationId("7,7")),
                            new Egress(new Direction("west"), new LocationId("5,7")),
                        ],
                        "New Square",
                        "???"
                    ),
                    "7,7" => new Location(
                        new LocationId("7,7"),
                        [
                            new Egress(new Direction("south"), new LocationId("7,6")),
                            new Egress(new Direction("west"), new LocationId("6,7")),
                        ],
                        "Rugby Pitch",
                        "???"
                    ),
                    "3,6" => new Location(
                        new LocationId("3,6"),
                        [
                            new Egress(new Direction("north"), new LocationId("3,7")),
                            new Egress(new Direction("south"), new LocationId("3,5")),
                            new Egress(new Direction("east"), new LocationId("4,6")),
                        ],
                        "St Andrew's Church",
                        "Despite being moved back to her usual spot on Grafton Street after the Luas Cross City works were completed, the statue of Molly Malone is back outside the church.",
                        Inventory::unoccupied(),
                        new Manifest([
                            $app[Registry::class]->findAutomaton(Entity::MOLLY_MALONE),
                        ])
                    ),
                    "4,6" => new Location(
                        new LocationId("4,6"),
                        [
                            new Egress(new Direction("north"), new LocationId("4,7")),
                            new Egress(new Direction("south"), new LocationId("4,5")),
                            new Egress(new Direction("west"), new LocationId("3,6")),
                        ],
                        "No 1 Grafton Street",
                        "The gates to the house of Trinity College's Provost are open. A low rumbling sound can be heard from inside the property. It's a little too close to the brown note for your liking.",
                        Inventory::unoccupied(),
                        Manifest::unoccupied(),
                        [],
                        [
                            LocationInventoryEventConfig::add(
                                $app[Registry::class]->findHoldable(Holdable::PHONE),
                                new PlayerDies("As you put your phone down it starts to ring, but with the same sound coming from the Provost's House. You answer and the phone emits a high-powered, high-pitch noise that causes your head to explode instantly. You are no longer alive.")
                            )
                        ]
                    ),
                    "5,6" => new Location(
                        new LocationId("5,6"),
                        [
                            new Egress(new Direction("north"), new LocationId("5,7")),
                            new Egress(new Direction("east"), new LocationId("6,6")),
                        ],
                        "Fellows Square",
                        "???"
                    ),
                    "6,6" => new Location(
                        new LocationId("6,6"),
                        [
                            new Egress(new Direction("north"), new LocationId("6,7")),
                            new Egress(new Direction("west"), new LocationId("5,6")),
                        ],
                        "Pomedoro Sphere",
                        "???"
                    ),
                    "7,6" => new Location(
                        new LocationId("7,6"),
                        [
                            new Egress(new Direction("north"), new LocationId("7,7")),
                            new Egress(new Direction("west"), new LocationId("6,6")),
                        ],
                        "Cricket Pitch",
                        "???"
                    ),
                    "3,5" => new Location(
                        new LocationId("3,5"),
                        [
                            new Egress(new Direction("north"), new LocationId("3,6")),
                        ],
                        "Murphy's Ice Cream",
                        "Somehow in this crazy world the ice cream is still frozen, still on sale and still delicious.",
                        Inventory::unoccupied(),
                        Manifest::unoccupied(),
                        [
                            new PlayerWins("Congratulations. You got your ice cream. You can go home now."),
                        ]
                    ),
                    "4,5" => new Location(
                        new LocationId("4,5"),
                        [
                            new Egress(new Direction("north"), new LocationId("4,6")),
                        ],
                        "Grafton Street",
                        "The way south is impassable. A mound of chicken nuggets two stories high has spilled out of McDonald's and is blocking the junction with Wicklow Street.",
                        Inventory::unoccupied(),
                        new Manifest([
                            $app[Registry::class]->findAutomaton(Entity::PIGEON),
                        ])
                    ),
                ]),
                $app[Registry::class],
                $startingLocation,
                new Inventory([$app[Registry::class]->findHoldable(Holdable::PHONE)])
            );
        });
    }
}
