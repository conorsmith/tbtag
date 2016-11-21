<?php
declare(strict_types=1);

namespace ConorSmith\TbtagTest\Unit\Ui;

use ConorSmith\Tbtag\CommandRepository;
use ConorSmith\Tbtag\Commands\ExitCommand;
use ConorSmith\Tbtag\Commands\HelpCommand;
use ConorSmith\Tbtag\Commands\LookCommand;
use ConorSmith\Tbtag\Commands\MoveCommand;
use ConorSmith\Tbtag\Ui\Dispatcher;
use ConorSmith\Tbtag\Ui\Input;
use ConorSmith\Tbtag\Ui\Interpreter;

class InterpreterTest extends \TestCase
{
    /**
     * @test
     * @dataProvider input
     */
    public function interprets_input(string $input, string $commandClass, array $args)
    {
        $dispatcher = $this->prophesize(Dispatcher::class);

        $interpreter = new Interpreter($dispatcher->reveal(), app(CommandRepository::class));

        $interpreter(new Input($input));

        $dispatcher->__invoke($commandClass, $args)
            ->shouldHaveBeenCalled();
    }

    public function input()
    {
        return [
            ["look", LookCommand::class, []],
            ["exit", ExitCommand::class, []],
            ["help", HelpCommand::class, []],
            ["move", MoveCommand::class, []],
            ["move north", MoveCommand::class, ["north"]],
            ["move blah", MoveCommand::class, ["blah"]],
            ["north", MoveCommand::class, ["north"]],
            ["blah", MoveCommand::class, ["blah"]],
        ];
    }
}
