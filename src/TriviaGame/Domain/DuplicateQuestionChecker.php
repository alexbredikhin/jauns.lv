<?php

namespace Core\TriviaGame\Domain;

interface DuplicateQuestionChecker
{
    /**
     * @return bool
     */
    public function check(): bool;
}

