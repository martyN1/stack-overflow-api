<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Criteria\Criteria;
use App\Domain\Entities\Answer;
use App\Domain\Repositories\AnswersRepository;
use App\Infrastructure\Services\BigQueryService;

class BigQueryAnswersRepository implements AnswersRepository
{
    protected string $tableName = 'bigquery-public-data.stackoverflow.posts_answers';

    public function __construct(
        private BigQueryService $bigQueryService
    ) {}

    /**
     * @return Answer[]
     * @throws \Exception
     */
    public function search(Criteria $criteria): array
    {
        $query = BigQueryQueryBuilder::getQuery(
            $this->tableName,
            ['id', 'body', 'score', 'comment_count', 'creation_date'],
            $criteria
        );

        $parameters = BigQueryQueryBuilder::getParameters($criteria);

        $answers = $this->bigQueryService->executeQuery($query, $parameters);

        return array_map(
            fn ($answer) => new Answer(
                id: $answer['id'],
                body: $answer['body'],
                score: $answer['score'],
                commentCount: $answer['comment_count'],
                creationDate: $answer['creation_date']->get()
            ),
            $answers
        );
    }
}