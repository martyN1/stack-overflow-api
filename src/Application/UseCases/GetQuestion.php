<?php

namespace App\Application\UseCases;

use App\Domain\Entities\QuestionDetail;
use App\Domain\Repositories\QuestionRepository;

class GetQuestion
{
    public function __construct(
        private QuestionRepository $questionRepository
    ) {}

    public function __invoke(int $id): QuestionDetail
    {
        return $this->questionRepository->find($id);
    }
}