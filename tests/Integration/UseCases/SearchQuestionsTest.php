<?php

namespace App\Tests\Integration\UseCases;

use App\Application\UseCases\SearchQuestions;
use App\Domain\Criteria\Criteria;
use App\Domain\Criteria\Filter\FilterOperator;
use App\Domain\Criteria\Filter\Filters;
use App\Domain\Criteria\Order\Order;
use App\Domain\Criteria\Order\OrderBy;
use App\Domain\Criteria\Order\OrderType;
use App\Domain\Criteria\Paginate\Pagination;
use App\Domain\Entities\Question;
use App\Domain\Repositories\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SearchQuestionsTest extends KernelTestCase
{
    public function test_search_questions_ok()
    {
        self::bootKernel();

        $criteria = new Criteria(
            Filters::fromValues([
                [
                    'field' => 'tag',
                    'operator' => FilterOperator::CONTAINS,
                    'value' => 'php'
                ]
            ]),
            new Order(
                new OrderBy('creation_date'),
                new OrderType('desc')
            ),
            new Pagination(0, 1)
        );

        $questionRepository = $this->createMock(QuestionRepository::class);
        $questionRepository
            ->expects(self::once())
            ->method('search')
            ->with($criteria)
            ->willReturn([
                new Question(
                    id: 123,
                    title: 'Test Question',
                    answerCount: 0,
                    tags: ['php'],
                    creationDate: new \DateTime()
                )
            ]);


        $searchQuestions = new SearchQuestions($questionRepository);
        $searchQuestions->__invoke($criteria);
    }
}