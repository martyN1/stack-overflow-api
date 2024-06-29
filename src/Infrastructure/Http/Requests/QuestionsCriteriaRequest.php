<?php

namespace App\Infrastructure\Http\Requests;


use App\Domain\Criteria\Filter\FilterOperator;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;

class QuestionsCriteriaRequest extends CriteriaRequest
{
    protected array $filters = [
        'tags' => FilterOperator::CONTAINS,
        'title' => FilterOperator::CONTAINS,
    ];

    #[Type('string')]
    protected $tags;

    #[Type('string')]
    protected $title;

    #[Assert\Choice(
        choices: ['creation_date', 'answer_count'],
        message: 'Invalid sort field',
    )]
    protected $sortBy;
}