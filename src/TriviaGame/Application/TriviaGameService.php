<?php

declare(strict_types=1);

namespace Core\TriviaGame\Application;

use Core\TriviaGame\Domain\AnswerRandomizer;
use Core\TriviaGame\Domain\CheckQuestionDto;
use Core\TriviaGame\Domain\ArrayDuplicateQuestionChecker;
use Core\TriviaGame\Domain\InvalidAnswerException;
use Core\TriviaGame\Domain\LastQuestionException;
use Core\TriviaGame\Domain\NotFoundQuestionException;
use Core\TriviaGame\Domain\QuestionFormatter;
use Core\TriviaGame\Domain\QuestionsRepository;
use Core\TriviaGame\Domain\TriviaGameEntity;
use Core\TriviaGame\Domain\TriviaGameRepository;
use Exception;
use Ramsey\Uuid\Uuid;

final class TriviaGameService
{
    private const MAX_GAME_PARTS = 20;

    /**
     * @param QuestionsRepository $questions
     * @param TriviaGameRepository $triviaGameRepository
     */
    public function __construct(
        private QuestionsRepository $questions,
        private TriviaGameRepository $triviaGameRepository,
    ) {}

    /**
     * @return array
     * @throws Exception
     */
    public function question(): array
    {
        $lastQuestion = $this->triviaGameRepository->getLastQuestion();

        if (null !== $lastQuestion && null === $lastQuestion->getUserAnswer()) {
            throw new LastQuestionException('Empty previous answer');
        }

        $getQuestionData = $this->questions->getQuestion();

        if ((new ArrayDuplicateQuestionChecker(
            $this->triviaGameRepository->getAllQuestions(),
            $getQuestionData['text']
        ))->check()) {
            return $this->question();
        }

        $correctAnswer = $getQuestionData['number'];

        $createNewGameQuestion = new TriviaGameEntity(
            Uuid::uuid4()->toString(),
            QuestionFormatter::replaceFirstMatch((string)$correctAnswer, 'What', $getQuestionData['text']),
            $correctAnswer,
            AnswerRandomizer::getAnswers($correctAnswer),
        );

        $this->triviaGameRepository->save(
            $createNewGameQuestion
        );

        return $createNewGameQuestion->toArray();
    }

    /**
     * @param int $answer
     * @return CheckQuestionDto
     */
    public function checkQuestion(int $answer): CheckQuestionDto
    {
        $getLastQuestion = $this->triviaGameRepository->getLastQuestion();

        if (null === $getLastQuestion) {
            throw new NotFoundQuestionException('Question does not exist', 404);
        }

        $countQuestions = $this->triviaGameRepository->countQuestions();
        $countCorrectAnswers = $countQuestions - 1;

        if (!in_array($answer, $getLastQuestion->getAnswers(), true)) {
            throw new InvalidAnswerException('Invalid answer! You answered on ' . $countCorrectAnswers, 400);
        }

        if ($answer !== $getLastQuestion->getCorrectAnswer()) {
            $this->triviaGameRepository->deleteAll();

            return new CheckQuestionDto(
                'Answer failed. Correct answer was ' . $getLastQuestion->getCorrectAnswer()
                . '. You answered on ' . $countCorrectAnswers . ' questions' . '. You lose! Try again!',
                400
            );
        }

        if ($countQuestions === self::MAX_GAME_PARTS) {
            $this->triviaGameRepository->deleteAll();

            return new CheckQuestionDto('Game finished. You win! You answered on 20 questions!');
        }

        $answerEntity = $getLastQuestion->setUserAnswer($answer);
        $this->triviaGameRepository->updateLastQuestion($answerEntity);

        return new CheckQuestionDto('Success');
    }

    public function cleanGame(): void
    {
        $this->triviaGameRepository->deleteAll();
    }
}

