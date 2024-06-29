<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Criteria\Criteria;
use App\Domain\Criteria\Filter\Filter;
use App\Domain\Criteria\Filter\FilterOperator;

class BigQueryQueryBuilder
{
    public static function getQuery(string $table, array $columns, Criteria $criteria): string
    {
        $columnsString = empty($columns)
            ? '*'
            : implode(', ', $columns);

        $filtersString = $criteria->hasFilters()
            ? "WHERE " . self::getFiltersString($criteria)
            : '';

        $orderString = $criteria->hasOrder()
            ? "ORDER BY {$criteria->order()->orderBy()->getValue()} {$criteria->order()->orderType()->getValue()}"
            : '';

        $limit = $criteria->pagination()->limit();
        $offset = $criteria->pagination()->offset();

        return "
            SELECT $columnsString
            FROM $table
            $filtersString
            $orderString
            LIMIT $limit
            OFFSET $offset;
        ";
    }

    public static function getParameters(Criteria $criteria) : array
    {
        $parameters = [];

        foreach ($criteria->filters()->filters() as $filter) {
            $parameters[$filter->field->getValue()] = self::getFilterValue($filter);
        }

       return $parameters;
    }

    protected static function getFilterValue(Filter $filter) : mixed
    {
        $operator = $filter->operator->getValue();
        $value = $filter->value->getValue();

        return match ($operator) {
            FilterOperator::CONTAINS, FilterOperator::NOT_CONTAINS => "%$value%",
            FilterOperator::STARTS_WITH, FilterOperator::NOT_STARTS_WITH => "$value%",
            FilterOperator::ENDS_WITH, FilterOperator::NOT_ENDS_WITH => "%$value",
            default => $value
        };
    }

    protected static function getFiltersString(Criteria $criteria): string
    {
        return implode(
            ' AND ',
            array_map(
                fn (Filter $filter) => self::getFilterString($filter),
                $criteria->filters()->filters()
            )
        );
    }

    protected static function getFilterString(Filter $filter) : string
    {
        $field = $filter->field->getValue();
        $operator = $filter->operator->getValue();

        $operator = match($operator) {
            FilterOperator::CONTAINS, FilterOperator::STARTS_WITH, FilterOperator::ENDS_WITH => 'LIKE',
            FilterOperator::NOT_CONTAINS, FilterOperator::NOT_STARTS_WITH, FilterOperator::NOT_ENDS_WITH => 'NOT LIKE',
            default => $operator
        };

        return "$field $operator @$field";
    }
}