<?php

namespace Payload\Bridge;

abstract class AbstractClaim
{
    abstract public function getCustomClaim(): array;

    public function __invoke(): array
    {
        return $this->getCustomClaim();
    }
}
