<?php

namespace ConorSmith\Tbtag\Providers;

use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\Direction;
use ConorSmith\Tbtag\Egress;
use ConorSmith\Tbtag\ExitCommand;
use ConorSmith\Tbtag\Game;
use ConorSmith\Tbtag\HelpCommand;
use ConorSmith\Tbtag\Location;
use ConorSmith\Tbtag\LocationId;
use ConorSmith\Tbtag\LookCommand;
use ConorSmith\Tbtag\Map;
use ConorSmith\Tbtag\MoveCommand;
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
        $this->app->bind(Game::class, function ($app) {
            return new Game(
                new Map([
                    "top-left" => $startingLocation = new Location(
                        new LocationId("top-left"),
                        [
                            new Egress(new Direction("east"), new LocationId("top-right")),
                            new Egress(new Direction("south"), new LocationId("bottom-left")),
                        ]
                    ),
                    "top-right" => new Location(
                        new LocationId("top-right"),
                        [
                            new Egress(new Direction("west"), new LocationId("top-left")),
                            new Egress(new Direction("south"), new LocationId("bottom-right")),
                        ]
                    ),
                    "bottom-left" => new Location(
                        new LocationId("bottom-left"),
                        [
                            new Egress(new Direction("east"), new LocationId("bottom-right")),
                            new Egress(new Direction("north"), new LocationId("top-left")),
                        ]
                    ),
                    "bottom-right" => new Location(
                        new LocationId("bottom-right"),
                        [
                            new Egress(new Direction("west"), new LocationId("bottom-left")),
                            new Egress(new Direction("north"), new LocationId("top-right")),
                        ]
                    ),
                ]),
                $startingLocation
            );
        });

        $this->app->bind(CommandRepository::class, function ($app) {
            return new CommandRepository([
                ExitCommand::class,
                HelpCommand::class,
                LookCommand::class,
                MoveCommand::class,
            ]);
        });
    }
}
