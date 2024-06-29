<?php

namespace App\Tests\Integration\Repositories;

use App\Domain\Criteria\Criteria;
use App\Domain\Criteria\Filter\FilterOperator;
use App\Domain\Criteria\Filter\Filters;
use App\Domain\Criteria\Order\Order;
use App\Domain\Criteria\Order\OrderBy;
use App\Domain\Criteria\Order\OrderType;
use App\Domain\Criteria\Paginate\Pagination;
use App\Domain\Entities\Question;
use App\Domain\Entities\QuestionDetail;
use App\Domain\Exceptions\QuestionNotFoundException;
use App\Infrastructure\Repositories\BigQueryQuestionRepository;
use App\Infrastructure\Services\BigQueryService;
use Google\Cloud\BigQuery\Date;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BigQueryQuestionRepositoryTest extends KernelTestCase
{
    public function test_find_question_ok()
    {
        self::bootKernel();

        $id = 123;

        $query = "
            SELECT *
            FROM bigquery-public-data.stackoverflow.posts_questions
            WHERE id = @id
            LIMIT 1;
        ";

        $parameters = ['id' => $id];

        $question = new QuestionDetail(
            id: $id,
            title: 'Test Question',
            body: 'Test Question',
            score: 0,
            viewCount: 0,
            answerCount: 0,
            commentCount: 0,
            tags: ['a', 'b', 'c'],
            creationDate: new \DateTime()
        );

        $bigQueryService = $this->createMock(BigQueryService::class);

        $bigQueryService
            ->expects($this->once())
            ->method('executeQuery')
            ->with($query, $parameters)
            ->willReturn([
                [
                    'id' => $question->id,
                    'title' => $question->title,
                    'body' => $question->body,
                    'score' => $question->score,
                    'view_count' => $question->viewCount,
                    'answer_count' => $question->answerCount,
                    'comment_count' => $question->commentCount,
                    'tags' => implode('|', $question->tags),
                    'creation_date' => new Date($question->creationDate),
                ]
            ]);

        $bigQueryQuestionRepository = new BigQueryQuestionRepository($bigQueryService);

        $questionReturned = $bigQueryQuestionRepository->find($id);
        $this->assertEquals($question, $questionReturned);
    }

    public function test_find_question_not_found()
    {
        self::bootKernel();

        $this->expectException(QuestionNotFoundException::class);

        $id = 123;

        $query = "
            SELECT *
            FROM bigquery-public-data.stackoverflow.posts_questions
            WHERE id = @id
            LIMIT 1;
        ";

        $parameters = ['id' => $id];

        $bigQueryService = $this->createMock(BigQueryService::class);

        $bigQueryService
            ->expects($this->once())
            ->method('executeQuery')
            ->with($query, $parameters)
            ->willReturn([]);

        $bigQueryQuestionRepository = new BigQueryQuestionRepository($bigQueryService);
        $bigQueryQuestionRepository->find($id);
    }

    public function test_search_ok()
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
            new Pagination(0, 2)
        );

        $query = "
            SELECT id, title, answer_count, tags, creation_date
            FROM bigquery-public-data.stackoverflow.posts_questions
            WHERE tag LIKE @tag
            ORDER BY creation_date desc
            LIMIT 2
            OFFSET 0;
        ";

        $parameters = ['tag' => '%php%'];

        $questions = [
            new Question(
                id: 1,
                title: 'Test Question',
                answerCount: 0,
                tags: ['php'],
                creationDate: new \DateTime()
            ),
            new Question(
                id: 2,
                title: 'Test Question 2',
                answerCount: 0,
                tags: ['php'],
                creationDate: new \DateTime()
            )
        ];

        $bigQueryService = $this->createMock(BigQueryService::class);

        $bigQueryService
            ->expects($this->once())
            ->method('executeQuery')
            ->with($query, $parameters)
            ->willReturn(array_map(fn($question) => [
                'id' => $question->id,
                'title' => $question->title,
                'answer_count' => $question->answerCount,
                'tags' => implode('|', $question->tags),
                'creation_date' => new Date($question->creationDate),
            ], $questions));

        $bigQueryQuestionRepository = new BigQueryQuestionRepository($bigQueryService);

        $questionsReturned = $bigQueryQuestionRepository->search($criteria);
        $this->assertEquals($questions, $questionsReturned);
    }
}