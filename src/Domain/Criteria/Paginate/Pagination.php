<?php

declare(strict_types=1);

namespace App\Domain\Criteria\Paginate;

final class Pagination
{
    public function __construct(private ?int $offset, private ?int $limit) {}

    public static function none(): Pagination
    {
        return new Pagination(null, null);
    }

    public function offset(): ?int
    {
        return $this->offset;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function isNone(): bool
    {
        return !$this->limit;
    }

    public function serialize(): string
    {
        return sprintf('%u.%u', $this->offset, $this->limit);
    }

    public function nextPage(): Pagination
    {
        if ($this->isNone()) {
            throw new \Exception('Page cannot be incremented');
        }

        return new self($this->offset + $this->limit, $this->limit);
    }
}
