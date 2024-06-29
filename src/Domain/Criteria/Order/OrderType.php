<?php

declare(strict_types=1);

namespace App\Domain\Criteria\Order;

use App\Domain\ValueObjects\ValueObject;
use InvalidArgumentException;

final class OrderType extends ValueObject
{
    public const ASC  = 'asc';
    public const DESC = 'desc';
    public const NONE = 'none';

    public function __construct(string $value)
    {
        if (!in_array($value, [
            self::ASC,
            self::DESC,
            self::NONE,
        ])) {
            throw new InvalidArgumentException("$value is not a valid order type");
        }

        parent::__construct($value);
    }

    public static function none() : OrderType
    {
        return new self(OrderType::NONE);
    }

    public function isNone(): bool
    {
        return $this->value === self::NONE;
    }

    protected function throwExceptionForInvalidValue($value): void
    {
        throw new InvalidArgumentException($value);
    }
}
