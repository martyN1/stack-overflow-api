<?php

namespace App\Infrastructure\Http\Requests;


use App\Domain\Criteria\Filter\FilterOperator;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;

class AnswersCriteriaRequest extends CriteriaRequest
{
    #[Assert\Choice(
        choices: ['creation_date', 'score', 'comment_count'],
        message: 'Invalid sort field',
    )]
    protected $sortBy = 'creation_date';
}