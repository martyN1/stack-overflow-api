<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\GetQuestion;
use App\Domain\Exceptions\QuestionNotFoundException;
use App\Infrastructure\Http\Resources\QuestionDetailResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/questions/{id}', methods: ['GET'])]
class GetQuestionController extends AbstractController
{
    public function __construct(
        private GetQuestion $getQuestion,
    ){}

    public function __invoke(int $id): JsonResponse
    {
        try {
            $question = $this->getQuestion->__invoke($id);
        } catch (QuestionNotFoundException $exception) {
            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $exception) {
            return new JsonResponse(
                ['error' => $exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(
            (new QuestionDetailResource($question))->toArray(),
            Response::HTTP_OK
        );
    }
}