<?php

namespace App\Domain\Criteria;

use InvalidArgumentException;

trait ValidatesCriteria
{
    public static function validateCriteria(Criteria $criteria) : void
    {
        self::validateFilters($criteria);
        self::validateOrder($criteria);
    }

    protected static function validateFilters(Criteria $criteria) : void
    {
        if ($criteria->hasFilters()) {
            foreach ($criteria->filters()->filters() as $filter) {
                $field = $filter->field()->getValue();

                if (!in_array($field, self::$filterableFields)) {
                    throw new InvalidArgumentException("$field is not a filterable field of " . get_called_class());
                }
            }
        }
    }

    protected static function validateOrder(Criteria $criteria) : void
    {
        if ($criteria->hasOrder()) {
            $orderBy = $criteria->order()->orderBy()->getValue();

            if (!in_array($orderBy, self::$sortableFields)) {
                throw new InvalidArgumentException("$orderBy is not a sortable field of " . get_called_class());
            }
        }
    }
}