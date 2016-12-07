<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag;

interface Holder extends Entity
{
    /**
     * @return void
     */
    public function addToInventory(Holdable $holdable);

    /**
     * @return void
     */
    public function removeFromInventory(Holdable $holdable);

    public function isHolding(Holdable $holdable): bool;
}
