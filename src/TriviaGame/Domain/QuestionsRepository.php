<?php

declare(strict_types=1);

namespace Core\TriviaGame\Domain;

interface QuestionsRepository
{
    /**
     * @return array
     */
    public function getQuestion(): array;
}

