<?php

namespace App\Domain\ValueObjects;

class ValueObject
{
    protected mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function getValue() : mixed
    {
        return $this->value;
    }
}