<?php

declare(strict_types=1);

namespace Payload\Domain\Collections;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

abstract class AbstractCollection implements IteratorAggregate, Countable
{
    /** @var Object[] */
    protected iterable $entities = [];

    /** @return ArrayIterator */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->entities);
    }

    public function count(): int
    {
        return count($this->entities);
    }

    public function getElements(): iterable
    {
        return $this->entities;
    }

    public function get($key)
    {
        return $this->entities[$key];
    }

    public function remove(): iterable
    {
        unset($this->entities);
        return $this;
    }

    public function empty(): bool
    {
        return static::count() === 0;
    }
    
    public function asArray(): array
    {
        return $this->entities;
    }
}
