<?php

namespace App\Domain\Exceptions;

class QuestionNotFoundException extends \Exception
{
    public function __construct() {
        parent::__construct("Question not found");
    }
}