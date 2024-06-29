<?php

declare(strict_types=1);

namespace App\Domain\Criteria\Filter;

use App\Domain\ValueObjects\ValueObject;
use InvalidArgumentException;

final class FilterOperator extends ValueObject
{
    public const EQUAL = '=';
    public const NOT_EQUAL = '!=';
    public const GT = '>';
    public const GTE = '>=';
    public const LT = '<';
    public const LTE = '<=';

    public const CONTAINS = 'CONTAINS';
    public const NOT_CONTAINS = 'NOT_CONTAINS';
    public const STARTS_WITH = 'STARTS_WITH';
    public const NOT_STARTS_WITH = 'NOT_STARTS_WITH';
    public const ENDS_WITH = 'ENDS_WITH';
    public const NOT_ENDS_WITH = 'NOT_ENDS_WITH';

    public const IN = 'IN';
    public const NOT_IN = 'NOT_IN';
    public const OR = 'OR';
    public const OR_NULL = 'OR_NULL';

    private static array $collectionTypes = [self::IN, self::NOT_IN,self::OR,self::OR_NULL];

    public function __construct(string $value)
    {
        if (!in_array($value, [
            self::EQUAL,
            self::NOT_EQUAL,
            self::GT,
            self::GTE,
            self::LT,
            self::LTE,
            self::CONTAINS,
            self::NOT_CONTAINS,
            self::STARTS_WITH,
            self::NOT_STARTS_WITH,
            self::ENDS_WITH,
            self::NOT_ENDS_WITH,
            self::IN,
            self::NOT_IN,
            self::OR,
            self::OR_NULL
        ])) {
            throw new InvalidArgumentException("$value is not a valid operator");
        }

        parent::__construct($value);
    }

    public function isCollectionType(): bool
    {
        return in_array($this->getValue(), self::$collectionTypes, true);
    }

    protected function throwExceptionForInvalidValue($value): void
    {
        throw new InvalidArgumentException(sprintf('The filter <%s> is invalid', $value));
    }
}
