<?php

namespace App\Infrastructure\Http\Resources;

use App\Domain\Entities\Answer;

class AnswerResource
{
    public function __construct(
        private Answer $answer
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->answer->id,
            'body' => $this->answer->body,
            'score' => $this->answer->score,
            'comment_count' => $this->answer->commentCount,
            'creation_date' => $this->answer->creationDate->format('Y-m-d H:i:s'),
        ];
    }
}