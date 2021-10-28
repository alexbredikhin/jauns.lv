<?php

declare(strict_types=1);

namespace Core\TriviaGame\Domain;

use Exception;

final class AnswerRandomizer
{
    private const QUESTIONS_MAX_ANSWERS = 3;
    private const SIMILAR_TO_CORRECT_ANSWER_RESULT_MIN_MAX = 500;

    /**
     * @throws Exception
     */
    public static function getRandomNumber(
        array $existedNumbers,
        int $questionMinNumber = 1,
        int $questionMaxNumber = 1500
    ): int {
        $randomInt = random_int($questionMinNumber, $questionMaxNumber);

        if (in_array($randomInt, $existedNumbers, true)) {
            return self::getRandomNumber($existedNumbers, $questionMinNumber, $questionMaxNumber);
        }

        return $randomInt;
    }

    /**
     * @throws Exception
     */
    public static function getAnswers(int|float $correctAnswer): array
    {
        $randomAnswers = [$correctAnswer];
        $questionMinAnswer = $correctAnswer >= self::SIMILAR_TO_CORRECT_ANSWER_RESULT_MIN_MAX
            ? $correctAnswer - self::SIMILAR_TO_CORRECT_ANSWER_RESULT_MIN_MAX
            : 1;
        $questionMaxAnswer = $correctAnswer + self::SIMILAR_TO_CORRECT_ANSWER_RESULT_MIN_MAX;

        for ($i = 0; $i < self::QUESTIONS_MAX_ANSWERS; $i++) {
            $randomAnswers[] = self::getRandomNumber(
                $randomAnswers,
                (int)$questionMinAnswer,
                (int)$questionMaxAnswer,
            );
        }
        shuffle($randomAnswers);

        return $randomAnswers;
    }
}

