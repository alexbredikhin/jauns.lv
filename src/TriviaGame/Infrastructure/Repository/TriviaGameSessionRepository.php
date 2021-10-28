<?php

declare(strict_types=1);

namespace Core\TriviaGame\Infrastructure\Repository;

use Core\TriviaGame\Domain\TriviaGameEntity;
use Core\TriviaGame\Domain\TriviaGameRepository;
use Illuminate\Contracts\Session\Session;

final class TriviaGameSessionRepository implements TriviaGameRepository
{
    private const TRIVIA_GAME_SESSION_KEY = 'answers';

    /**
     * @param Session $sessionHandler
     */
    public function __construct(private Session $sessionHandler,)
    {}

    /**
     * @param TriviaGameEntity $questionEntity
     */
    public function save(TriviaGameEntity $questionEntity,): void
    {
        $currentAnswers = $this->getAllQuestions();
        $currentAnswers[] = $questionEntity;

        $this->sessionHandler->put(
            self::TRIVIA_GAME_SESSION_KEY,
            $currentAnswers
        );
    }

    public function deleteAll(): void
    {
        $this->sessionHandler->forget(self::TRIVIA_GAME_SESSION_KEY);
    }

    /**
     * @return TriviaGameEntity []
     */
    public function getAllQuestions(): array
    {
        return $this->sessionHandler->get(self::TRIVIA_GAME_SESSION_KEY) ?? [];
    }

    /**
     * @return int
     */
    public function countQuestions(): int
    {
        return count($this->getAllQuestions());
    }

    public function getLastQuestion(): ?TriviaGameEntity
    {
        $getAllQuestions = $this->getAllQuestions();

        if ([] === $getAllQuestions) {
            return null;
        }

        $lastKey = array_key_last($getAllQuestions);

        return $this->getAllQuestions()[$lastKey];
    }

    /**
     * @param TriviaGameEntity $triviaGameEntity
     */
    public function updateLastQuestion(TriviaGameEntity $triviaGameEntity,): void
    {
        $getAnswers = $this->getAllQuestions();
        array_pop($getAnswers);
        $getAnswers[] = $triviaGameEntity;
        $this->sessionHandler->put(
            self::TRIVIA_GAME_SESSION_KEY,
            $getAnswers
        );
    }
}

