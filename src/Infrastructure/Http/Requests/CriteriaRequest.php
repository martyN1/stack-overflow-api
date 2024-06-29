<?php

namespace App\Infrastructure\Http\Requests;


use App\Domain\Criteria\Criteria;
use App\Domain\Criteria\Filter\Filters;
use App\Domain\Criteria\Order\Order;
use App\Domain\Criteria\Order\OrderBy;
use App\Domain\Criteria\Order\OrderType;
use App\Domain\Criteria\Paginate\Pagination;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints as Assert;

abstract class CriteriaRequest extends BaseRequest
{
    protected array $filters = [];

    #[Type('integer')]
    #[Assert\GreaterThan(0)]
    protected int $page = 1;

    #[Type('integer')]
    #[Assert\LessThanOrEqual(50)]
    protected int $perPage = 10;


    protected $sortBy = null;

    #[Assert\Choice(
        choices: ['asc', 'desc'],
        message: 'Invalid sort direction.',
    )]
    protected $sortDirection = 'asc';

    protected function getFilters(): array
    {
        $filters = [];

        foreach ($this->filters as $field => $operator) {
            if (array_key_exists($field, $this->getParameters())) {
                $filters[] = [
                    'field' => $field,
                    'operator' => $operator,
                    'value' => $this->getParameters()[$field],
                ];
            }
        }

        return $filters;
    }

    public function getCriteria(): Criteria
    {
        $offset = ($this->page - 1) * $this->perPage;
        $filters = $this->getFilters();

        return new Criteria(
            Filters::fromValues($filters),
            $this->sortBy
                ? new Order( new OrderBy($this->sortBy), new OrderType($this->sortDirection) )
                : Order::none(),
            new Pagination($offset, $this->perPage)
        );
    }
}