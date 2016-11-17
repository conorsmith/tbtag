<?php
declare(strict_types=1);

require_once "vendor/autoload.php";

$map = new Map([
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
]);


$repl = new Repl(
    new Interpreter(
        new Game($map, $startingLocation),
        new CommandRepository([
            'look'  => LookCommand::class,
            'exit'  => ExitCommand::class,
            'north' => MoveCommand::class,
            'south' => MoveCommand::class,
            'east'  => MoveCommand::class,
            'west'  => MoveCommand::class,
            'help'  => HelpCommand::class,
        ])
    )
);

$repl->begin();
