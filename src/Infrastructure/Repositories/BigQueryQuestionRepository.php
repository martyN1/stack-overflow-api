<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Criteria\Criteria;
use App\Domain\Entities\Question;
use App\Domain\Entities\QuestionDetail;
use App\Domain\Exceptions\QuestionNotFoundException;
use App\Domain\Repositories\QuestionRepository;
use App\Infrastructure\Services\BigQueryService;

class BigQueryQuestionRepository implements QuestionRepository
{
    protected string $tableName = 'bigquery-public-data.stackoverflow.posts_questions';

    public function __construct(
        private BigQueryService $bigQueryService
    ) {}
    public function find(int $id): QuestionDetail
    {
        $query = "
            SELECT *
            FROM $this->tableName
            WHERE id = @id
            LIMIT 1;
        ";

        $parameters = ['id' => $id];

        $questions = $this->bigQueryService->executeQuery($query, $parameters);

        if (empty($questions)) {
            throw new QuestionNotFoundException();
        }

        $question = $questions[0];

        return new QuestionDetail(
            id: $question['id'],
            title: $question['title'],
            body: $question['body'],
            score: $question['score'],
            viewCount: $question['view_count'],
            answerCount: $question['answer_count'],
            commentCount: $question['comment_count'],
            tags: explode('|', $question['tags']),
            creationDate: $question['creation_date']->get()
        );
    }

    /**
     * @return Question[]
     */
    public function search(Criteria $criteria): array
    {
        $query = BigQueryQueryBuilder::getQuery(
            $this->tableName,
            ['id', 'title', 'answer_count', 'tags', 'creation_date'],
            $criteria
        );

        $parameters = BigQueryQueryBuilder::getParameters($criteria);

        $questions = $this->bigQueryService->executeQuery($query, $parameters);

        return array_map(
            fn ($question) => new Question(
                id: $question['id'],
                title: $question['title'],
                answerCount: $question['answer_count'],
                tags: explode('|', $question['tags']),
                creationDate: $question['creation_date']->get()
            ),
            $questions
        );
    }
}