<?php

declare(strict_types=1);

namespace Payload\Domain\Collections;

class ClaimCollection extends AbstractCollection
{
    protected iterable $entities = [];

    public function add(string $key, string | int | array | null | bool $claim): self
    {
        $this->entities[$key] = $claim;
        return $this;
    }

    public function get($key)
    {
        return $this->entities[$key];
    }
}
