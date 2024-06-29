<?php

namespace App\Infrastructure\Http\Resources;

use App\Domain\Entities\Question;

class QuestionResource
{
    public function __construct(
        private Question $question
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->question->id,
            'title' => $this->question->title,
            'answer_count' => $this->question->answerCount,
            'tags' => $this->question->tags,
            'creation_date' => $this->question->creationDate->format('Y-m-d H:i:s'),
        ];
    }
}