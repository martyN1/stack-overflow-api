<?php

namespace App\Application\UseCases;

use App\Domain\Criteria\Criteria;
use App\Domain\Entities\Answer;
use App\Domain\Repositories\AnswersRepository;

class GetQuestionAnswers
{
    public function __construct(
        private AnswersRepository $answerRepository
    ) {}

    /**
     * @return Answer[]
     */
    public function __invoke(Criteria $criteria): array
    {
        return $this->answerRepository->search($criteria);
    }
}