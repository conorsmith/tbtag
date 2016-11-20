<?php
declare(strict_types=1);

namespace ConorSmith\Tbtag\Ui;

class TabularPayload extends Payload
{
    /** @var array */
    private $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function getData()
    {
        return $this->value;
    }
}
