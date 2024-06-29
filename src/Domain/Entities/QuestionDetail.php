<?php

namespace App\Domain\Entities;

readonly class QuestionDetail
{
    public function __construct(
        public int $id,
        public string $title,
        public string $body,
        public int $score,
        public int $viewCount,
        public int $answerCount,
        public int $commentCount,
        public array $tags,
        public \DateTimeInterface $creationDate,
    ) {}
}