<?php

namespace App\Tests\Integration\Repositories;

use App\Domain\Criteria\Criteria;
use App\Domain\Criteria\Filter\FilterOperator;
use App\Domain\Criteria\Filter\Filters;
use App\Domain\Criteria\Order\Order;
use App\Domain\Criteria\Order\OrderBy;
use App\Domain\Criteria\Order\OrderType;
use App\Domain\Criteria\Paginate\Pagination;
use App\Domain\Entities\Answer;
use App\Infrastructure\Repositories\BigQueryAnswersRepository;
use App\Infrastructure\Services\BigQueryService;
use Google\Cloud\BigQuery\Date;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BigQueryAnswerRepositoryTest extends KernelTestCase
{
    public function test_search_ok()
    {
        self::bootKernel();

        $criteria = new Criteria(
            Filters::fromValues([
                [
                    'field' => 'parent_id',
                    'operator' => FilterOperator::EQUAL,
                    'value' => 123
                ]
            ]),
            new Order(
                new OrderBy('creation_date'),
                new OrderType('desc')
            ),
            new Pagination(0, 2)
        );

        $query = "
            SELECT id, body, score, comment_count, creation_date
            FROM bigquery-public-data.stackoverflow.posts_answers
            WHERE parent_id = @parent_id
            ORDER BY creation_date desc
            LIMIT 2
            OFFSET 0;
        ";

        $parameters = ['parent_id' => 123];

        $answers = [
            new Answer(
                id: 1,
                body: 'body 1',
                score: 3,
                commentCount: 0,
                creationDate: new \DateTime()
            ),
            new Answer(
                id: 2,
                body: 'body 2',
                score: 2,
                commentCount: 0,
                creationDate: new \DateTime()
            ),
        ];

        $bigQueryService = $this->createMock(BigQueryService::class);

        $bigQueryService
            ->expects($this->once())
            ->method('executeQuery')
            ->with($query, $parameters)
            ->willReturn(array_map(fn($answer) => [
                'id' => $answer->id,
                'body' => $answer->body,
                'score' => $answer->score,
                'comment_count' => $answer->commentCount,
                'creation_date' => new Date($answer->creationDate),
            ], $answers));

        $bigQueryAnswersRepository = new BigQueryAnswersRepository($bigQueryService);

        $answersReturned = $bigQueryAnswersRepository->search($criteria);
        $this->assertEquals($answers, $answersReturned);
    }
}