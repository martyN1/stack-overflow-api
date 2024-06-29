<?php

namespace App\Tests\Integration\UseCases;

use App\Application\UseCases\GetQuestionAnswers;
use App\Domain\Criteria\Criteria;
use App\Domain\Criteria\Filter\FilterOperator;
use App\Domain\Criteria\Filter\Filters;
use App\Domain\Criteria\Order\Order;
use App\Domain\Criteria\Order\OrderBy;
use App\Domain\Criteria\Order\OrderType;
use App\Domain\Criteria\Paginate\Pagination;
use App\Domain\Entities\Answer;
use App\Domain\Repositories\AnswersRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetQuestionAnswersTest extends KernelTestCase
{
    public function test_search_questions_ok()
    {
        self::bootKernel();

        $criteria = new Criteria(
            Filters::fromValues([
                [
                    'field' => 'parent_id',
                    'operator' => FilterOperator::EQUAL,
                    'value' => 432
                ]
            ]),
            new Order(
                new OrderBy('creation_date'),
                new OrderType('desc')
            ),
            new Pagination(0, 1)
        );

        $answersRepository = $this->createMock(AnswersRepository::class);
        $answersRepository
            ->expects(self::once())
            ->method('search')
            ->with($criteria)
            ->willReturn([
                new Answer(
                    id: 123,
                    body: 'answer body 1',
                    score: 10,
                    commentCount: 10,
                    creationDate: new \DateTime()
                )
            ]);


        $getQuestionAnswers = new GetQuestionAnswers($answersRepository);
        $getQuestionAnswers->__invoke($criteria);
    }
}