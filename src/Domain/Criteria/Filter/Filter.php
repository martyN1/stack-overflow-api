<?php

declare(strict_types=1);

namespace App\Domain\Criteria\Filter;

final readonly class Filter
{
    public function __construct(
        public FilterField $field,
        public FilterOperator $operator,
        public FilterValue $value
    ) {
    }

    public static function fromValues(array $values): self
    {
        return new self(
            new FilterField($values['field']),
            new FilterOperator($values['operator']),
            new FilterValue($values['value'])
        );
    }

    public function serialize(): string
    {
        return sprintf('%s.%s.%s', $this->field->getValue(), $this->operator->getValue(), $this->value->getValue());
    }
}
