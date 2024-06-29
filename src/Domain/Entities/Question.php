<?php

namespace App\Domain\Entities;

readonly class Question
{
    public function __construct(
        public int $id,
        public string $title,
        public int $answerCount,
        public array $tags,
        public \DateTimeInterface $creationDate,
    ) {}
}