<?php

declare(strict_types=1);

namespace Core\TriviaGame\Domain;

final class ArrayDuplicateQuestionChecker implements DuplicateQuestionChecker
{
    /**
     * @param TriviaGameEntity [] $previousQuestions $previousQuestions
     * @param string $currentQuestion
     */
    public function __construct(private array $previousQuestions, private string $currentQuestion)
    {}

    /**
     * @return bool
     */
    public function check(): bool
    {
        foreach ($this->previousQuestions as $question) {
            $makeQuestion = QuestionFormatter::replaceFirstMatch(
                'What',
                (string)$question->getCorrectAnswer(),
                $question->getQuestion()
            );

            if ($makeQuestion === $this->currentQuestion) {
                return true;
            }
        }

        return false;
    }
}
