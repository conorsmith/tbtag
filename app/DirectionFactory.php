<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

use InvalidArgumentException;

class DirectionFactory
{
    private $validSlugs;

    public function __construct(array $validSlugs)
    {
        $this->validSlugs = $validSlugs;
    }

    public function fromSlug(string $slug)
    {
        if (in_array($slug, $this->validSlugs)) {
            return new Direction($slug);
        }

        if (strlen($slug) === 1) {
            $slug = collect($this->validSlugs)
                ->first(function ($validSlug) use ($slug) {
                    return $slug === substr($validSlug, 0, 1);
                });

            if (!is_null($slug)) {
                return new Direction($slug);
            }
        }

        throw new InvalidArgumentException;
    }
}
