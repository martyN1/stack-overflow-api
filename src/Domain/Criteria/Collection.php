<?php

namespace App\Domain\Criteria;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

abstract class Collection implements Countable, IteratorAggregate,ArrayAccess
{
    public function __construct(private array $items)
    {

    }

    abstract protected function type(): string;

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items());
    }

    public function count(): int
    {
        return count($this->items());
    }

    protected function items(): array
    {
        return $this->items;
    }

    public function offsetExists($key): bool
    {
        return isset($this->items[$key]);
    }

    public function offsetGet($key): mixed
    {
        return $this->items[$key];
    }

    public function offsetSet($key, $value): void
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    public function offsetUnset($key): void
    {
        unset($this->items[$key]);
    }

}