<?php

namespace App\Domain\Repositories;



use App\Domain\Criteria\Criteria;
use App\Domain\Entities\Question;
use App\Domain\Entities\QuestionDetail;

interface QuestionRepository
{
    public function find(int $id): ?QuestionDetail;

    /**
     * @return Question[]
     */
    public function search(Criteria $criteria): array;
}