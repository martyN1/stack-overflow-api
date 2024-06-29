<?php

namespace App\Tests\Unit;

use App\Domain\Criteria\Criteria;
use App\Domain\Criteria\Filter\FilterOperator;
use App\Domain\Criteria\Filter\Filters;
use App\Domain\Criteria\Order\Order;
use App\Domain\Criteria\Order\OrderBy;
use App\Domain\Criteria\Order\OrderType;
use App\Domain\Criteria\Paginate\Pagination;
use App\Infrastructure\Repositories\BigQueryQueryBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BigQueryQueryBuilderTest extends KernelTestCase
{
    public function test_get_query()
    {
        self::bootKernel();

        $criteria = new Criteria(
            Filters::fromValues([
                [
                    'field' => 'column1',
                    'operator' => FilterOperator::EQUAL,
                    'value' => 'value1'
                ]
            ]),
            new Order(
                new OrderBy('column2'),
                new OrderType('desc')
            ),
            new Pagination(0, 5)
        );

        $expected = "
            SELECT column1, column2
            FROM table
            WHERE column1 = @column1
            ORDER BY column2 desc
            LIMIT 5
            OFFSET 0;
        ";


        $query = BigQueryQueryBuilder::getQuery(
            'table',
            ['column1', 'column2'],
            $criteria
        );

        $this->assertSame($expected, $query);
    }

    public function test_get_query_without_columns()
    {
        self::bootKernel();

        $criteria = new Criteria(
            Filters::fromValues([
                [
                    'field' => 'column1',
                    'operator' => FilterOperator::EQUAL,
                    'value' => 'value1'
                ]
            ]),
            new Order(
                new OrderBy('column2'),
                new OrderType('desc')
            ),
            new Pagination(0, 5)
        );

        $expected = "
            SELECT *
            FROM table
            WHERE column1 = @column1
            ORDER BY column2 desc
            LIMIT 5
            OFFSET 0;
        ";


        $query = BigQueryQueryBuilder::getQuery(
            'table',
            [],
            $criteria
        );

        $this->assertSame($expected, $query);
    }
}