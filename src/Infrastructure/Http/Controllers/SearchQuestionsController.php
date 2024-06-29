<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\SearchQuestions;
use App\Infrastructure\Http\Requests\QuestionsCriteriaRequest;
use App\Infrastructure\Http\Resources\QuestionResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/questions', methods: ['GET'])]
class SearchQuestionsController extends AbstractController
{
    public function __construct(
        private SearchQuestions $searchQuestions,
    ){}

    public function __invoke(QuestionsCriteriaRequest $request): JsonResponse
    {
        try {
            $questions = $this->searchQuestions->__invoke($request->getCriteria());
        } catch (\Exception $exception) {
            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(
            array_map(fn ($question) => (new QuestionResource($question))->toArray(), $questions),
            Response::HTTP_OK
        );
    }
}