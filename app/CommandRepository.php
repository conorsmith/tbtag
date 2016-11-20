<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use InvalidArgumentException;

class CommandRepository
{
    /** @var array */
    private $classesBySlug;

    /** @var array */
    private $classesByAlias;

    public function __construct(array $commands)
    {
        $this->classesBySlug = collect($commands)
            ->keyBy(function ($command) {
                return $command::SLUG;
            })
            ->toArray();

        $this->classesByAlias = collect($commands)
            ->map(function ($command) {
                return collect($command::ALIASES)
                    ->keyBy(function ($alias) {
                        return $alias;
                    })
                    ->map(function ($alias) use ($command) {
                        return $command;
                    })
                    ->toArray();
            })
            ->flatMap(function ($aliases) {
                return $aliases;
            })
            ->toArray();
    }

    public function getSlugs()
    {
        return array_merge(
            array_keys($this->classesBySlug),
            array_keys($this->classesByAlias)
        );
    }

    public function getCommands()
    {
        return $this->classesBySlug;
    }

    public function find(string $commandSlug)
    {
        if (array_key_exists($commandSlug, $this->classesBySlug)) {
            return $this->classesBySlug[$commandSlug];
        }

        if (array_key_exists($commandSlug, $this->classesByAlias)) {
            return $this->classesByAlias[$commandSlug];
        }

        throw new InvalidArgumentException;
    }
}
