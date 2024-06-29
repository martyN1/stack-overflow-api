<?php

namespace App\Infrastructure\Http\Resources;

use App\Domain\Entities\QuestionDetail;

class QuestionDetailResource
{
    public function __construct(
        private QuestionDetail $question
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->question->id,
            'title' => $this->question->title,
            'body' => $this->question->body,
            'score' => $this->question->score,
            'view_count' => $this->question->viewCount,
            'answer_count' => $this->question->answerCount,
            'comment_count' => $this->question->commentCount,
            'tags' => $this->question->tags,
            'creation_date' => $this->question->creationDate->format('Y-m-d H:i:s'),
        ];
    }
}