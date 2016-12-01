<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use ConorSmith\Tbtag\Commands\InspectsArea;
use ConorSmith\Tbtag\Ui\Input;
use ConorSmith\Tbtag\Ui\InteractionsPayload;
use ConorSmith\Tbtag\Ui\MissingArgument;
use ConorSmith\Tbtag\Ui\Output;
use ConorSmith\Tbtag\Ui\Payload;
use DomainException;
use InvalidArgumentException;

class TurnProcessor
{
    /** @var Interpreter */
    private $interpreter;

    /** @var Game */
    private $game;

    /** @var Output */
    private $output;

    public function __construct(Interpreter $interpreter, Game $game, Output $output)
    {
        $this->interpreter = $interpreter;
        $this->game = $game;
        $this->output = $output;
    }

    public function __invoke(Input $input)
    {
        $command = null;

        try {
            $command = $this->interpreter->__invoke($input);

        } catch (MissingArgument $e) {
            $this->output->payload(new Payload($e->getMessage()));

        } catch (DomainException $e) {
            $this->output->payload(new Payload($e->getMessage()));

        } catch (InvalidArgumentException $e) {
            $this->output->payload(new Payload("I don't understand what you mean."));
        }

        $this->game->processAutomatonActions();

        if (!is_null($command) && $command instanceof InspectsArea) {
            $this->output->payload(InteractionsPayload::fromLocation($this->game->getCurrentLocation()));
        }

        $this->game->turnComplete();
    }
}
