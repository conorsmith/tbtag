<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

use ConorSmith\Tbtag\Handler;
use InvalidArgumentException;

class Controller
{
    /** @var Interpreter */
    private $interpreter;

    /** @var Handler */
    private $handler;

    public function __construct(Interpreter $interpreter, Handler $handler)
    {
        $this->interpreter = $interpreter;
        $this->handler = $handler;
    }

    public function __invoke(Input $input): array
    {
        try {
            $command = $this->interpreter->__invoke($input);
            return $this->handler->__invoke($command, $input);

        } catch (MissingArgument $e) {
            return [new Payload($e->getMessage())];

        } catch (InvalidArgumentException $e) {
            return [new Payload("I don't understand what you mean.")];
        }
    }
}
