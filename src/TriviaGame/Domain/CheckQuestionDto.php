<?php

declare(strict_types=1);

namespace Core\TriviaGame\Domain;

final class CheckQuestionDto
{
    /**
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(private string $message, private int $statusCode = 200)
    {}

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}

