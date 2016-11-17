<?php
declare(strict_types=1);

class Repl
{
    private $interpreter;

    public function __construct(Interpreter $interpreter)
    {
        $this->interpreter = $interpreter;
    }

    public function begin()
    {
        $this->parseCommand("look");
    }

    private function awaitInput()
    {
        echo "What do you want to do? ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        $this->parseCommand(str_replace(["\n", "\r"], "", $line));
    }

    private function parseCommand(string $input)
    {
        $input = new Input($input);

        try {
            $command = $this->interpreter->__invoke($input);

        } catch (InvalidArgumentException $e) {
            $this->print("I don't understand what you mean.");
            $this->awaitInput();
            return;
        }

        try {
            $this->print($command($input));
            $this->awaitInput();
            return;

        } catch (ExitGame $e) {
            $this->print("Goodbye!");
            return;
        }
    }

    private function print(string $message)
    {
        echo "\033[1m" . $message . "\033[0m\n";
    }
}
