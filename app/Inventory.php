<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use DomainException;
use LogicException;

class Inventory
{
    public static function unoccupied(): self
    {
        return new self([]);
    }

    private $contents;

    public function __construct(array $contents)
    {
        if (collect($contents)
            ->reject(function ($content) {
                return $content instanceof Holdable;
            })
            ->count() > 0
        ) {
            throw new LogicException("All contents of an inventory must be holdable.");
        }

        $this->contents = $contents;
    }

    public function getContents(): array
    {
        return $this->contents;
    }

    public function add(Holdable $holdable)
    {
        $this->contents[] = $holdable;
    }

    public function remove(Holdable $holdable)
    {
        foreach ($this->contents as $key => $content) {
            if ($content->getSlug() === $holdable->getSlug()) {
                unset($this->contents[$key]);
                return;
            }
        }

        throw new DomainException("That item is not here.");
    }

    public function contains(Holdable $holdable)
    {
        foreach ($this->contents as $content) {
            if ($content->getSlug() === $holdable->getSlug()) {
                return true;
            }
        }

        return false;
    }
}
