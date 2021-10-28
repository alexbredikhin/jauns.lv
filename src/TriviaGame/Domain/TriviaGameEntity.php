<?php

declare(strict_types=1);

namespace Core\TriviaGame\Domain;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;

final class TriviaGameEntity implements Arrayable
{
    /**
     * @param string $uuid
     * @param string $question
     * @param int $correctAnswer
     * @param array $answers
     * @param int|null $userAnswer
     */
    public function __construct(
        private string $uuid,
        private string $question,
        private int $correctAnswer,
        private array $answers,
        private ?int $userAnswer = null
    ) {}

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @return array
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    /**
     * @return int
     */
    public function getCorrectAnswer(): int
    {
        return $this->correctAnswer;
    }

    /**
     * @return int|null
     */
    public function getUserAnswer(): ?int
    {
        return $this->userAnswer;
    }

    /**
     * @param int $userAnswer
     * @return $this
     */
    public function setUserAnswer(int $userAnswer): TriviaGameEntity
    {
        $this->userAnswer = $userAnswer;

        return $this;
    }

    /**
     * @return array
     */
    #[ArrayShape(['question' => "string", 'answers' => "array"])]
    public function toArray(): array
    {
        return [
            'question' => $this->question,
            'answers' => $this->answers,
        ];
    }
}

