<?php

namespace App\Application\UseCases;

use App\Domain\Criteria\Criteria;
use App\Domain\Entities\Question;
use App\Domain\Repositories\QuestionRepository;

class SearchQuestions
{
    public function __construct(
        private QuestionRepository $questionRepository
    ) {}

    /**
     * @return Question[]
     */
    public function __invoke(Criteria $criteria): array
    {
        return $this->questionRepository->search($criteria);
    }
}