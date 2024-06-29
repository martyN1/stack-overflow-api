<?php

namespace App\Tests\Integration\UseCases;

use App\Application\UseCases\GetQuestion;
use App\Domain\Entities\QuestionDetail;
use App\Domain\Repositories\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GetQuestionTest extends KernelTestCase
{
    public function test_get_question_ok()
    {
        self::bootKernel();

        $id = 123;

        $questionRepository = $this->createMock(QuestionRepository::class);
        $questionRepository
            ->expects(self::once())
            ->method('find')
            ->with($id)
            ->willReturn(new QuestionDetail(
                id: $id,
                title: 'Test Question',
                body: 'Test Question',
                score: 0,
                viewCount: 0,
                answerCount: 0,
                commentCount: 0,
                tags: [],
                creationDate: new \DateTime()
            ));


        $getQuestion = new GetQuestion($questionRepository);
        $getQuestion->__invoke($id);
    }
}