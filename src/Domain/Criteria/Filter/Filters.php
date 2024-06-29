<?php

declare(strict_types=1);

namespace App\Domain\Criteria\Filter;

use App\Domain\Criteria\Collection;

final class Filters extends Collection
{
    public static function fromValues(array $values): self
    {
        return new self(array_map(self::filterBuilder(), $values));
    }

    private static function filterBuilder(): callable
    {
        return fn (array $values) => Filter::fromValues($values);
    }  

    public function filters(): array
    {
        return $this->items();
    }

    public function get(string $key): Filter
    {
        return $this->offsetGet($key);
    }

    public function remove(string $key): void
    {
        $this->offsetUnset($key);
    }

    public function has(string $key): bool
    {
        return $this->offsetExists($key);
    }

    public function add(Filter $filter): void
    {
        $this->offsetSet($filter->field->getValue(),$filter);
    }

    public function serialize(): string
    {
        return array_reduce(
            $this->items(),
            fn (string $accumulate, Filter $filter) => sprintf('%s^%s', $accumulate, $filter->serialize()),
            ''
        );
    }

    protected function type(): string
    {
        return Filter::class;
    }
}
