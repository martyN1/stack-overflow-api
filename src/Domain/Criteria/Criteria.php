<?php

declare(strict_types=1);

namespace App\Domain\Criteria;

use App\Domain\Criteria\Filter\Filter;
use App\Domain\Criteria\Filter\Filters;
use App\Domain\Criteria\Order\Order;
use App\Domain\Criteria\Order\OrderBy;
use App\Domain\Criteria\Order\OrderType;
use App\Domain\Criteria\Paginate\Pagination;

final class Criteria
{
    public function __construct(
        private Filters $filters,
        private Order $order,
        private Pagination $pagination,
    ) {}

    public static function empty() : self
    {
        return self::fromValues(['filters' => []]);
    }

    public static function fromValues(array $values): Criteria
    {
        return new self(
            Filters::fromValues($values['filters']),
            isset($values['order_by'])
                ? new Order(
                    new OrderBy($values['order_by']),
                    new OrderType($values['order_type'])
                )
                : Order::none(),
            isset($values['limit'])
                ? new Pagination(
                    $values['offset'],
                    $values['limit']
                )
                : Pagination::none()
        );
    }

    public function hasFilters(): bool
    {
        return $this->filters->count() > 0;
    }

    public function hasOrder(): bool
    {
        return !$this->order->isNone();
    }

    public function hasPagination(): bool
    {
        return (bool) $this->pagination;
    }

    public function plainFilters(): array
    {
        return $this->filters->filters();
    }

    public function filters(): Filters
    {
        return $this->filters;
    }

    public function order(): Order
    {
        return $this->order;
    }

    public function pagination(): ?Pagination
    {
        return $this->pagination;
    }

    public function serialize(): string
    {
        return sprintf(
            '%s~~%s~~%s',
            $this->filters->serialize(),
            $this->order->serialize(),
            $this->pagination->serialize(),
        );
    }

    public function nextPage(): Criteria
    {
        return new self($this->filters, $this->order, $this->pagination->nextPage());
    }

    public function remove(string $key): void
    {
        $this->filters()->remove($key);
    }

    public function has(string $key): bool
    {
        return $this->filters()->has($key);
    }

    public function get(string $key): Filter
    {
        return $this->filters()->get($key);
    }

    public function add(Filter $filter)
    {
        $this->filters()->add($filter);
    }
}
