<?php

namespace App\Domain\Repositories;

use App\Domain\Criteria\Criteria;
use App\Domain\Entities\Answer;

interface AnswersRepository
{
    /**
     * @return Answer[]
     */
    public function search(Criteria $criteria): array;
}