<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\GetQuestionAnswers;
use App\Domain\Criteria\Filter\Filter;
use App\Domain\Criteria\Filter\FilterOperator;
use App\Infrastructure\Http\Requests\AnswersCriteriaRequest;
use App\Infrastructure\Http\Resources\AnswerResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/questions/{id}/answers', methods: ['GET'])]
class GetQuestionAnswersController extends AbstractController
{
    public function __construct(
        private GetQuestionAnswers $getQuestionAnswers,
    ){}

    public function __invoke(int $id, AnswersCriteriaRequest $request): JsonResponse
    {
        try {
            $criteria = $request->getCriteria();

            $criteria->add(
                Filter::fromValues([
                    'field' => 'parent_id',
                    'operator' => FilterOperator::EQUAL,
                    'value' => $id
                ])
            );

            $answers = $this->getQuestionAnswers->__invoke($criteria);
        } catch (\Exception $exception) {
            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(
            array_map(fn ($answer) => (new AnswerResource($answer))->toArray(), $answers),
            Response::HTTP_OK
        );
    }
}