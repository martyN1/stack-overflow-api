<?php

namespace App\Domain\Entities;

readonly class Answer
{
    public function __construct(
        public int $id,
        public string $body,
        public int $score,
        public int $commentCount,
        public \DateTimeInterface $creationDate,
    ) {}
}